import HTTP from './HTTP'

class Galaxy extends HTTP {
  public root = 'galaxy.api'

  constructor() {
    super()
  }

  public async galaxy(x: number = 0, y: number = 0, z: number = 0) {
    const response = await this.http.get(route(`${this.root}`, { x: x, y: y, z: z }))
    return response.data
  }
}

export default new Galaxy()
