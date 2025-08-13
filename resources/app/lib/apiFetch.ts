import store from '@root/resources/app/store/rootStore'

export interface FetchOptions extends Omit<RequestInit, 'body'> {
    headers?: Record<string, string>
    body?: Record<string, any> | BodyInit | null
    responseType?: 'json' | 'blob' | 'text'
}

export default async <T>(url: string, options: FetchOptions = {}): Promise<{ data: T; status: number }> => {
    const token = store.state.auth.access_token
    const headers = {
        'Authorization': `Bearer ${ token }`,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...options.headers
    }

    const fetchOptions: RequestInit = {
        ...options,
        headers,
        body: options.body && typeof options.body === 'object' && !(options.body instanceof FormData)
            ? JSON.stringify(options.body)
            : options.body
    }

    const response = await fetch(url, fetchOptions)

    if (!response.ok) {
        const contentType = response.headers.get('Content-Type')
        const isJson = contentType?.includes('application/json')
        const errorData = isJson ? await response.json() : null

        throw {
            status: response.status,
            message: errorData?.message || `HTTP error with status: ${ response.status }`,
            data: errorData
        }
    }

    if (options.responseType === 'blob') {
        const blobData = (await response.blob()) as any
        return { data: blobData, status: response.status }
    }

    if (options.responseType === 'text') {
        const textData = (await response.text()) as any
        return { data: textData, status: response.status }
    }

    const contentType = response.headers.get('Content-Type')
    const isJson = contentType?.includes('application/json')
    const jsonData = isJson ? await response.json() : null

    return { data: jsonData, status: response.status }
}
