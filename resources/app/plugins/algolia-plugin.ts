import store from '@root/resources/app/store/rootStore'
import { searchClient } from "@algolia/client-search"
import type { SearchClient, SearchResponse } from "@algolia/client-search"

class AlgoliaPlugin {
    private client: SearchClient

    constructor() {
        const { app_id, secret } = store.state.auth.algolia || { app_id: '', secret: '' }

        if (!app_id || !secret) {
            throw new Error('Algolia app_id or secret is not defined.')
        }

        this.client = searchClient(app_id, secret)
    }

    async search<T>({ query, page, perPage, indexName, filter }: SearchParams): Promise<SearchResult<T>> {
        const params: Record<string, any> = {
            page: page - 1, // Algolia pages are zero-indexed
            hitsPerPage: perPage,
        }

        if (filter) params.filters = `status.name:${ filter }`

        const response: SearchResponse<T> = await this.client.searchSingleIndex({
            indexName,
            searchParams: {
                query,
                ...params,
            },
        })

        return {
            current_page: (response.page ?? 0) + 1,
            per_page: perPage,
            last_page: response.nbPages ?? 0,
            total: response.nbHits ?? 0,
            data: response.hits as T[],
        }
    }
}

export default AlgoliaPlugin
