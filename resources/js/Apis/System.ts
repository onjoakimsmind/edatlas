import HTTP from './HTTP'

class System extends HTTP {
  public root = 'systems.api'

  constructor() {
    super()
  }
}

export default new System()
