import HTTP from './HTTP'

class Search extends HTTP {
  public root = 'search.api'

  constructor() {
    super()
  }

  public async search(query: string): Promise<any> {
    const data = await this.http.get(route(`${this.root}`, { q: query }))
    return data
  }
}

export default new Search()
