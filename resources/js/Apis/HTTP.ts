import axios, { Axios } from 'axios'

class HTTP {
  http: Axios = axios.create({
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
  })

  public root = `http.api`

  public async index(page: number = 1, column: string = 'id', order: string = 'asc'): Promise<any> {
    console.log(this.root)
    const systems = await this.http.get(
      route(`${this.root}.index`, { page: page, sort: column, order: order })
    )
    return systems.data
  }

  public async get(name: string): Promise<any> {
    const data = await this.http.get(route(`${this.root}.show`, { system: name }))
    return data
  }

  public async find(query: string): Promise<any> {
    const data = await this.http.get(route(`${this.root}.search`, { q: query }))
    return data
  }
}

export default HTTP
