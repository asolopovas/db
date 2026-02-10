import { expect, test } from "@playwright/test";

type DuplicateMode = "all" | "materials_products" | "services_only";

type LoginResponse = {
  access_token?: string;
  data?: {
    access_token?: string;
  };
};

type OrderPayload = {
  id: number;
  products: unknown[];
  orderServices: unknown[];
  orderMaterials: unknown[];
};

const sourceOrderId = Number(process.env.E2E_SOURCE_ORDER_ID || "3933");
const username = process.env.E2E_USERNAME;
const password = process.env.E2E_PASSWORD;

test.describe("order duplication", () => {
  test("creates real duplicated order and returns navigable id", async ({
    request,
    baseURL,
  }) => {
    expect(baseURL, "Base URL is required").toBeTruthy();
    expect(username, "Set E2E_USERNAME").toBeTruthy();
    expect(password, "Set E2E_PASSWORD").toBeTruthy();
    expect(
      sourceOrderId,
      "Set E2E_SOURCE_ORDER_ID to a valid order id",
    ).toBeGreaterThan(0);

    const token = await loginAndGetToken(request);

    const sourceOrder = await fetchOrder(request, token, sourceOrderId);

    expect(
      sourceOrder.products.length > 0 &&
        sourceOrder.orderMaterials.length > 0 &&
        sourceOrder.orderServices.length > 0,
      "Source order must have products, materials and services for meaningful mode checks",
    ).toBeTruthy();

    await assertDuplicateMode(request, token, sourceOrder, "all");
    await assertDuplicateMode(
      request,
      token,
      sourceOrder,
      "materials_products",
    );
    await assertDuplicateMode(request, token, sourceOrder, "services_only");
  });
});

async function loginAndGetToken(
  request: Parameters<typeof test>[0]["request"],
) {
  const response = await request.post("/auth/login", {
    data: {
      username,
      password,
    },
  });

  expect(
    response.ok(),
    `Login failed with status ${response.status()}`,
  ).toBeTruthy();

  const payload = (await response.json()) as LoginResponse;
  const token = payload.data?.access_token || payload.access_token;

  expect(token, "No access token returned from /auth/login").toBeTruthy();

  return token as string;
}

async function fetchOrder(
  request: Parameters<typeof test>[0]["request"],
  token: string,
  orderId: number,
) {
  const response = await request.get(`/api/orders/${orderId}`, {
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: "application/json",
    },
  });

  expect(
    response.ok(),
    `Fetching order ${orderId} failed with ${response.status()}`,
  ).toBeTruthy();

  return (await response.json()) as OrderPayload;
}

async function duplicateOrder(
  request: Parameters<typeof test>[0]["request"],
  token: string,
  orderId: number,
  mode: DuplicateMode,
) {
  const response = await request.post(`/api/orders/${orderId}/duplicate`, {
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: "application/json",
      "Content-Type": "application/json",
    },
    data: { mode },
  });

  expect(
    response.ok(),
    `Duplicate mode '${mode}' failed with status ${response.status()}`,
  ).toBeTruthy();

  const payload = (await response.json()) as {
    id?: number;
    item?: { id?: number };
    data?: { id?: number; item?: { id?: number } };
  };

  const newId =
    payload.id ||
    payload.item?.id ||
    payload.data?.id ||
    payload.data?.item?.id;

  expect(
    newId,
    `Duplicate mode '${mode}' did not return new order id`,
  ).toBeTruthy();
  expect(newId, "Duplicate returned same order id").not.toBe(orderId);

  return Number(newId);
}

async function assertDuplicateMode(
  request: Parameters<typeof test>[0]["request"],
  token: string,
  sourceOrder: OrderPayload,
  mode: DuplicateMode,
) {
  const newOrderId = await duplicateOrder(request, token, sourceOrder.id, mode);
  const duplicated = await fetchOrder(request, token, newOrderId);

  if (mode === "all") {
    expect(duplicated.products.length).toBe(sourceOrder.products.length);
    expect(duplicated.orderMaterials.length).toBe(
      sourceOrder.orderMaterials.length,
    );
    expect(duplicated.orderServices.length).toBe(
      sourceOrder.orderServices.length,
    );
    return;
  }

  if (mode === "materials_products") {
    expect(duplicated.products.length).toBe(sourceOrder.products.length);
    expect(duplicated.orderMaterials.length).toBe(
      sourceOrder.orderMaterials.length,
    );
    expect(duplicated.orderServices.length).toBe(0);
    return;
  }

  expect(duplicated.products.length).toBe(0);
  expect(duplicated.orderMaterials.length).toBe(0);
  expect(duplicated.orderServices.length).toBe(
    sourceOrder.orderServices.length,
  );
}
