<template>
  <order-components ref="detailsContainer">
    <template #title>Details</template>
    <template #loading>
      <spinner v-if="loading" #loading />
    </template>
    <template v-for="(modal, key) in modals" :key="key">
      <modal
        v-if="modal.shown"
        class="send"
        :controls="true"
        @close="() => (modal.shown = false)"
        @yes="modal.action"
        @no="() => (modal.shown = false)"
      >
        {{ modal.message }}
      </modal>
    </template>
    <modal
      v-if="showModal"
      class="send"
      :controls="true"
      @close="() => (showModal = false)"
      @yes="send"
    >
      Do you wish to send this order?
    </modal>
    <modal v-if="pdf" class="pdf" :controls="false" @close="() => (pdf = null)">
      <embed
        id="pdf"
        :src="pdf"
        type="application/pdf"
        width="1000"
        height="550"
      />
    </modal>
    <modal v-if="mailBox" class="textarea" @close="mailBox = false">
      <template #title>Mail Message</template>
      <input-field set="setOrderStats" loc="order.cc" :alerts="alerts" />
      <text-area
        class="mb-4"
        :labelOn="false"
        set="setOrderStats"
        loc="order.mail_message"
        :action="saveMailMessage"
      />
      <base-button
        class="btn-action bg-sky-600 self-end text-white"
        @click="defaultMailMessage"
      >
        Default
      </base-button>
    </modal>
    <modal v-if="paymentTerms" class="textarea" @close="paymentTerms = false">
      <template #title>Payment Terms</template>
      <text-area
        :labelOn="false"
        class="mb-4"
        set="setOrderStats"
        loc="order.payment_terms"
      />
      <base-button
        class="btn-action bg-sky-600 self-end text-white"
        @click="() => save(true)"
      >
        Save
      </base-button>
    </modal>
    <modal v-if="notesBox" class="textarea" @close="notesBox = false">
      <template #title>Notes</template>
      <text-area
        :labelOn="false"
        class="mb-4"
        set="setOrderStats"
        loc="order.notes"
      />

      <base-button
        class="btn-action bg-sky-600 self-end text-white"
        @click="defaultMailMessage"
      >
        Default
      </base-button>
    </modal>

    <template #meta>
      <!-- Order Meta -->
      <DisplayField
        v-for="(value, key) in orderMeta.items"
        :key="key"
        :label="value.label"
        :format="value.format"
        :alerts="alerts"
        :loc="`${orderMeta.parent}.${key}`"
      />
    </template>
    <div class="max-w-[1004px] mx-auto shadow-lg">
      <div class="flex flex-wrap bg-stone-200 mx-auto mt-4 py-4">
        <!-- Col 1 -->
        <div class="flex flex-col gap-3 items-start w-full p-4">
          <!-- Order Settings -->
          <div class="grid md:grid-cols-2 lg:grid-cols-4 w-full gap-2 mb-4">
            <template v-for="(value, key) in orderSettings.items">
              <input-search
                wrap="w-full md:w-auto"
                class="w-full lg:max-w-[195px]"
                :is="value.type"
                :set="orderSettings.setter"
                :skip="value.skip"
                :format="value.format"
                :cast="value.cast"
                :alerts="alerts"
                :loc="`${orderInputs.parent}.${key}`"
              />
            </template>
          </div>
          <!-- Order  Inputs  -->
          <div
            class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 mb-3 w-full"
          >
            <template v-for="(value, key) in orderInputs.items">
              <component
                class="w-full lg:w-auto"
                :label="key"
                :is="value.type"
                :set="orderInputs.setter"
                :format="value.format"
                :cast="value.cast"
                :alerts="alerts"
                :loc="`${orderInputs.parent}.${key}`"
              />
            </template>
          </div>
          <div class="flex flex-col w-full">
            <hr />
            <div
              class="order-controls grid md:grid-cols-2 lg:grid-cols-4 gap-2"
            >
              <base-button
                class="btn-action bg-yellow-500"
                @click="viewInvoice"
              >
                View Invoice
              </base-button>
              <base-button
                class="btn-action bg-green-600 text-white"
                @click="downloadInvoice"
              >
                Download Invoice
              </base-button>
              <base-button
                class="btn-action bg-orange-400"
                @click="customerOrders"
              >
                View Related Orders
              </base-button>
            </div>
          </div>
          <!-- Messages and Communications -->
          <div class="flex flex-col mb-4 w-full">
            <hr class="mb-1" />
            <div
              class="messages-controls grid grid-cols-2 lg:grid lg:grid-cols-4 gap-2"
            >
              <base-button
                class="btn-action bg-sky-600 text-white"
                @click="() => (mailBox = true)"
              >
                Mail
              </base-button>
              <base-button
                class="btn-action bg-sky-600 text-white"
                @click="() => (notesBox = true)"
              >
                Notes
              </base-button>
              <base-button
                class="btn-action bg-sky-600 text-white col-span-2 lg:col-span-1"
                @click="() => (paymentTerms = true)"
              >
                Payment Terms
              </base-button>
              <base-button
                class="btn-action bg-red-400 col-span-2 lg:col-span-1"
                @click="viewReverseCharge"
              >
                Reverse Charge Invoice
              </base-button>
              <div
                class="toggle-container col-span-2 lg:col-span-1 lg:col-start-4 justify-self-end"
              >
                <label
                  class="toggle-label select-none cursor-pointer"
                  for="reverse-charge"
                  >Reverse Charge</label
                ><input
                  id="reverse-charge"
                  type="checkbox"
                  class="checkbox"
                  v-model="reverseCharge"
                /><label
                  for="reverse-charge"
                  class="switch cursor-pointer"
                ></label>
              </div>
            </div>
          </div>
          <div class="flex gap-4 w-full justify-end pb-12">
            <div class="flex flex-col gap-2 min-w-[320px]">
              <p
                class="text-xs font-semibold uppercase tracking-wide text-neutral-600"
              >
                Duplicate Copy
              </p>
              <div class="grid grid-cols-3 gap-2">
                <base-button
                  class="btn-action bg-indigo-600 hover:bg-indigo-700 text-white text-sm"
                  @click="() => duplicateOrder('all')"
                >
                  All
                </base-button>
                <base-button
                  class="btn-action bg-indigo-500 hover:bg-indigo-600 text-white text-sm"
                  @click="() => duplicateOrder('materials_products')"
                >
                  Materials + Products
                </base-button>
                <base-button
                  class="btn-action bg-indigo-400 hover:bg-indigo-500 text-white text-sm"
                  @click="() => duplicateOrder('services_only')"
                >
                  Services Only
                </base-button>
              </div>
            </div>
            <div class="w-1/2 grid grid-cols-2 gap-2 col-start-2 items-center">
              <p
                class="col-span-2 text-xs font-semibold uppercase tracking-wide text-neutral-600"
              >
                Order Actions
              </p>
              <base-button
                class="btn-action bg-yellow-300 hover:bg-yellow-400 text-black"
                @click="() => (showModal = true)"
              >
                Send
              </base-button>
              <base-button
                class="btn-action bg-green-700 hover:bg-green-800 text-white"
                @click="save(false)"
              >
                Save
              </base-button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </order-components>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { useStore } from "vuex";
import { filters } from "@root/resources/app/lib/global-helpers";
import { useRouter } from "vue-router";
import apiFetch from "@root/resources/app/lib/apiFetch";
import DisplayField from "@components/form-components/DisplayField.vue";

const router = useRouter();
const store = useStore();

const modals = ref<Record<string, Modal>>({});
const showModal = ref(false);
const loading = ref(false);
const pdf = ref<string | null>(null);
const mailBox = ref(false);
const notesBox = ref(false);
const paymentTerms = ref(false);
const alerts = ref<any[]>([]);

const orderSettings = ref<StoreUpdater>({
  setter: "setOrderStats",
  parent: "order",
  items: {
    customer: {
      type: "input-search",
      format: (item: any) => `${item.firstname} ${item.lastname}`,
    },
    status: {
      type: "input-search",
      skip: [2],
    },
    project: {
      type: "input-search",
      format: (item: any) => (item ? item.street : ""),
    },
    company: { type: "input-search" },
  },
});

const orderInputs = ref<StoreUpdater>({
  setter: "setOrderStats",
  parent: "order",
  items: {
    date_due: {
      type: "input-field",
      cast: "date",
      format: (item: any) => filters.dateFormat(item, "yyyy-MM-dd"),
    },
    vat: { type: "input-field" },
    discount: { type: "input-field" },
    due: { type: "input-field" },
    due_amount: { type: "input-field" },
  },
});
const orderMeta = ref<StoreUpdater>({
  setter: "setOrderStats",
  parent: "order",
  items: {
    created_at: {
      label: "Created At",
      type: "display-field",
      format: filters.dateFormat,
    },
    updated_at: {
      label: "Updated At",
      type: "display-field",
      format: filters.dateFormat,
    },
    sent: { type: "display-field", format: filters.dateFormat },
  },
});
const reverseCharge = computed({
  get: () => store.state.order.reverse_charge,
  set: (value) => {
    store.commit("setOrderStats", { value, loc: "reverse_charge" });
    save(false);
  },
});
const token = computed(() => store.state.auth.access_token);

async function send() {
  showModal.value = false;
  loading.value = true;
  await store.dispatch("orderSend");
  loading.value = false;
}

function save(silent = false) {
  if (!alerts.value.length) {
    loading.value = true;
    store.dispatch("orderSave", silent).then(() => (loading.value = false));
  }
}
const defaultMailMessage = async () => {
  const url =
    store.state.order.status.name === "Quote"
      ? "/api/settings/get/quotation_message"
      : "/api/settings/get/invoice_message";
  try {
    const { data } = await apiFetch(url, { method: "GET" });
    store.commit("setOrderStats", {
      value: data,
      loc: "mail_message",
    });
    save(true);
  } catch (error) {
    console.error("Failed to fetch mail message:", error);
  }
};
function saveMailMessage() {
  store.dispatch("orderSaveItem", {
    mail_message: store.state.order.mail_message,
  });
}
const downloadInvoice = async () => {
  try {
    const { data } = await apiFetch<Blob>(
      `/api/orders/${store.state.order.id}/download`,
      {
        method: "GET",
        responseType: "blob",
      },
    );

    const blob = data as Blob;
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement("a");
    link.href = url;
    const order = store.state.order;
    const customerName = [order.customer.firstname, order.customer.lastname]
      .filter(Boolean)
      .join("-");
    link.download = `Order #${order.id} - ${customerName}.pdf`;
    document.body.appendChild(link);
    link.click();
    link.remove();
    window.URL.revokeObjectURL(url);
  } catch (error) {
    console.error("Failed to download invoice:", error);
  }
};
function viewReverseCharge() {
  const time = Date.now();
  const params = `width=${window.screen.availWidth / 2},height=${window.screen.availHeight - 60}`;
  window.open(
    `/api/orders/${store.state.order.id}/pdf-reverse-charge?token=${token.value}&time=${time}`,
    "_blank",
    params,
  );
}
function viewInvoice() {
  const time = Date.now();
  const params = `location=true,resizable=yes,scrollbars=yes,status=yes,width=${window.screen.availWidth / 2},height=${window.screen.availHeight - 60},left=0,top=0`;

  window.open(
    `/api/orders/${store.state.order.id}/pdf-default?token=${token.value}&time=${time}`,
    "_blank",
    params,
  );
}
function customerOrders() {
  const order = store.state.order;
  const query = [order.customer.firstname, order.customer.lastname]
    .filter(Boolean)
    .join(" ");

  router.push({ name: "orders", query: { search: query } });
}

async function duplicateOrder(
  mode: "all" | "materials_products" | "services_only" = "all",
) {
  try {
    const id = store.state.order.id;
    const response = await apiFetch(`/api/orders/${id}/duplicate`, {
      method: "POST",
      body: { mode },
    });
    const newId =
      (response as any)?.data?.id || (response as any)?.data?.item?.id;

    if (!newId) {
      throw new Error("Duplicate created but no order id returned.");
    }

    const targetPath = `/orders/${newId}/details`;

    try {
      await router.push({ path: targetPath });
    } catch {
      window.location.assign(targetPath);
    }
  } catch (error) {
    console.error("Failed to duplicate order:", error);
  }
}
</script>
