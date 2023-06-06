import HTTP from './HTTP'

class System extends HTTP {
  public root = 'systems.api'

  constructor() {
    super()
  }

  public async factions(name: string) {
    const response = await this.http.get(route(`${this.root}.factions`, { system: name }))
    return response.data.factions
  }

  public async galaxy(x: number = 0, y: number = 0, z: number = 0) {
    const response = await this.http.get(route(`galaxy.api`, { x, y, z }))
    return response.data
  }

  public async scroll(scroll: string) {
    const response = await this.http.get(route(`galaxy.api.scroll`, { scroll }))
    return response.data
  }

  public async grid()
  {
    const response = await this.http.get(route(`galaxy.api.grid`))
    return response.data
  }
}

export default new System()
