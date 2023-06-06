<template>
  <Full title="Galaxy Map">
    <Loader v-if="loading" class="absolute z-10 top-20 left-5" />
    <div class="absolute inset-x-0 z-10 w-1/6 mx-auto -mt-1 shadow-xl search min-h-12 top-24 bg-neutral-900 text-anzac-500">
      <input v-model="query" @keyup="onSearch" type="text" class="w-full h-10 p-2 outline-none bg-neutral-900 text-anzac-500" placeholder="Search" />
      <div v-if="searchResults.length > 0 && setActiveSystem">
        <ul v-for="system of searchResults" :key="system.address">
          <li @click="setActiveSystem(system)" class="system">
            {{ system.name }}
          </li>
        </ul>
      </div>
    </div>
    <div ref="galaxy" id="map" class="w-full h-full" />
  </Full>
</template>
<script lang="ts" setup>
import { onMounted, ref, Ref, watch } from 'vue'
import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js'
import Stats from "three/examples/jsm/libs/stats.module.js"
import gsap from 'gsap'

import Full from '@/Layouts/Full.vue'

import Loader from '@/Components/Loader/index.vue'

import { SystemsApi } from '@/Apis'

interface System {
  name: string
  address: number
  x: number
  y: number
  z: number
  distance: number
}

const galaxy = ref(null)
const activeSystem: Ref<System | null> = ref(null)
const query: Ref<string> = ref("")
const loading: Ref<boolean> = ref(false)
const maxYCoord: Ref<number> = ref(0)
const minYCoord: Ref<number> = ref(0)
const maxXCoord: Ref<number> = ref(0)
const minXCoord: Ref<number> = ref(0)
const chunks: Ref<any> = ref([])
const points: Ref<THREE.Points[]> = ref([])
const geometries: Ref<THREE.BufferGeometry[]> = ref([])
const cameraPos: Ref<{ x: number; y: number; z: number }> = ref({ x: 0, y: 0, z: 0 })
const positionsHR: Ref<string[]> = ref([])
let domElement: HTMLElement
const scene: THREE.Scene = new THREE.Scene()
const camera: THREE.PerspectiveCamera = new THREE.PerspectiveCamera()
const renderer: THREE.WebGLRenderer = new THREE.WebGLRenderer({ antialias: false, powerPreference: "high-performance" })
let controls: OrbitControls
let raycaster: THREE.Raycaster
let INTERSECTED: any
let SELECTED: any
let mouse: THREE.Vector2
let textureLoader: THREE.TextureLoader
let shape: THREE.Texture
const ringGeo = new THREE.RingGeometry(5, 6, 32)
const ringMat = new THREE.MeshBasicMaterial({ color: 0xffff00, side: THREE.DoubleSide })
const ring: THREE.Mesh = new THREE.Mesh(ringGeo, ringMat)
let sizes: Record<string, number>
let stats: Stats

const systems: Ref<System[]> = ref([] as System[])
const searchResults: Ref<System[]> = ref([] as System[])
const scroll: Ref<string> = ref('')

onMounted(() => {
  init()
  Camera()
  Controls()
  animate()
  window.addEventListener("resize", onWindowResize, false)
})

watch(cameraPos, (newVal) => {
  console.log(newVal)
})


const fetchData = async (x: number = 0, y: number = 0, z: number = 0, redraw: boolean = false) => {
  loading.value = true
  systems.value = []
  const response = await SystemsApi.galaxy(x,y,z)
  systems.value.push(response.systems)
  scroll.value = response.scroll

  generateGalaxy(response.systems, redraw)

  if(scroll.value) {
    scrollData()
  }
  loading.value = false
}

const scrollData = async () => {
  loading.value = true
  const response = await SystemsApi.scroll(scroll.value)
  systems.value = response.systems
  scroll.value = response.scroll

  if(!response.scroll) {
    loading.value = false
    scroll.value = ''
    return
  }

  generateGalaxy(response.systems)

  scrollData();
}

const onSearch = async () => {
  searchResults.value = []
  if (query.value.length > 0) {
    const response = await SystemsApi.find(query.value)
    searchResults.value = response.data
  }
}
const setActiveSystem = (system: System) => {
  controls.enabled = false
  activeSystem.value = system
  query.value = system.name
  searchResults.value = [system]
  const worldDirection = camera.getWorldDirection(new THREE.Vector3())
  gsap.to(controls.target, {
    duration: 2,
    x: system.x,
    y: system.y,
    z: system.z,
    onUpdate: () => {
      controls.update()
    },
  })

  gsap.to(camera.position, {
    duration: 2,
    x: system.x - worldDirection.x * 50,
    y: system.y - worldDirection.y * 50,
    z: system.z - worldDirection.z * 50,
    onUpdate: () => {
      controls.update()
    },
    onComplete: () => {
      controls.enabled = true
      camera.updateProjectionMatrix()
      for(let i = 0; i < points.value.length; i++) {
        points.value[i].removeFromParent()
      }
      points.value = []
      fetchData(activeSystem.value?.x, activeSystem.value?.y, activeSystem.value?.z, true)
    },
  })
}

const init = async () => {
  domElement = galaxy.value! as HTMLElement
  sizes = {
    width: galaxy.value!["clientWidth"],
    height: galaxy.value!["clientHeight"],
  }

  renderer.setSize(sizes.width, sizes.height)
  domElement.appendChild(renderer.domElement)

  textureLoader = new THREE.TextureLoader()
  shape = textureLoader.load("/storage/1.png")

  scene.background = new THREE.Color("rgb(0,0,0)")

  stats = new Stats()
  stats.dom.classList.add("stats")
  stats.dom.removeAttribute("style")
  domElement.appendChild(stats.dom)
  SystemsApi.grid().then((res) => {
    const values = [res.min_y_coord.value, res.max_y_coord.value, res.min_x_coord.value, res.max_x_coord.value];
    const max = 250
    const min = -250
    drawGrid(min, max, min, max)
  })
  fetchData()
}

const Camera = () => {
  camera.fov = 45
  camera.near = 1
  camera.far = 500000
  camera.aspect = sizes.width / sizes.height
  camera.lookAt(0, 0, 0)
  camera.up.set(0, -1, 0)
  //camera.position.set(-214, -207, -267)
}

const Controls = () => {
  controls = new OrbitControls(camera, renderer.domElement)
  controls.target.set(0, 0, 0)
  controls.screenSpacePanning = true
  controls.enableDamping = true
  controls.minDistance = 10
  controls.maxDistance = 150
  controls.keyPanSpeed = 250
  controls.keys = {
    LEFT: "ArrowLeft",
    UP: "ArrowUp",
    RIGHT: "ArrowRight",
    BOTTOM: "ArrowDown",
  }
  //controls.addEventListener("change", updateRender)
  controls.addEventListener("end", () => {
    /*console.log(Math.abs(camera.position.x), Math.abs(cameraPos.value.x))
    if(Math.abs(camera.position.x) >= Math.abs(cameraPos.value.x) + 50 || Math.abs(camera.position.y) >= Math.abs(cameraPos.value.y) + 50 || Math.abs(camera.position.y) >= Math.abs(cameraPos.value.y) + 50) {
      cameraPos.value = camera.position
      fetchData(camera.position.x, camera.position.y, camera.position.z)
    }*/
  })
  //controls.listenToKeyEvents(window)
  controls.update()
}

const updateRender = () => {
  cameraPos.value = camera.position
  const timeout = setTimeout(() => {
    console.log('camera stopped moving')
  }, 1500)
  clearTimeout(timeout)
}

const Raycaster = () => {
  raycaster = new THREE.Raycaster()
  //raycaster.params.Points!.threshold = 0.3
  mouse = new THREE.Vector2()
  raycaster.near = 0
  raycaster.far = 1000000
  /*window.addEventListener("pointermove", onDocumentMouseMove, false)
  window.addEventListener("pointerdown", onDocumentClick, false)*/
  document.addEventListener("keypress", onKeyPress)
}

const generateGalaxy = (systems: System[], redraw: boolean = false) => {

  const geometry: THREE.BufferGeometry = new THREE.BufferGeometry()

  const names: string[] = []

  const color = new THREE.Color()
  color.set("rgb(255,255,255)")
  let positionData: string[] = []
  if (geometry.userData.positions) {
    positionData = geometry.userData.positions
  }


  const positions: number[] = []
  const colors: number[] = []
  for (let i = 0; i < systems.length; i++) {
    const system = systems[i]
    if(positionsHR.value.includes(`${system.x}, ${system.y}, ${system.z}`)) {
      console.log('already loaded')
      continue
    }
    color.setHex(Math.random() * 0xffffff)
    colors.push(color.r, color.g, color.b)
    positions.push(parseFloat((Math.round(system.x * 100) / 100).toFixed(2)), parseFloat((Math.round(system.y * 100) / 100).toFixed(2)), parseFloat((Math.round(system.z * 100) / 100).toFixed(2)))
    
    names.push(system.name)
    positionsHR.value.push(`${system.x}, ${system.y}, ${system.z}`)
  }
  
  geometry.setAttribute("position", new THREE.Float32BufferAttribute(positions, 3))
  geometry.setAttribute("color", new THREE.Float32BufferAttribute(colors, 3))
  if (geometry.userData.names) {
    const nameData = geometry.userData.names
    geometry.userData.names = nameData.concat(names)
  } else {
    geometry.userData.names = names
  }
  if (geometry.userData.positions) {
    geometry.userData.positions = positionData.concat(positionsHR.value)
  } else {
    geometry.userData.positions = positionsHR.value
  }
  const material = new THREE.PointsMaterial({
    color: "white",
    size: 2,
    depthWrite: false,
    depthTest: false,
    depthFunc: THREE.GreaterEqualDepth,
    sizeAttenuation: true,
    blending: THREE.AdditiveBlending,
    vertexColors: true,
    transparent: true,
    alphaMap: shape,
  })
  const p = new THREE.Points(geometry, material)
  geometry.attributes.position.needsUpdate = true
  geometry.attributes.color.needsUpdate = true
  geometry.userData.needsUpdate = true
  geometries.value.push(geometry)
  points.value.push(p)
  scene.add(p)
}

const findInChunk = (chunks: any, x: number, y: number, z: number) => {
  return chunks.some((el: any) => {
    for(let i = 0; i < el.length; i++) {
      if(el[i][0] == x && el[i][1] == y && el[i][2] == z) {
        return true
      }
    }
  })
}

const drawGrid = (minYCoord: number, maxYCoord: number, minXCoord: number, maxXCoord: number) => {
  const material = new THREE.LineBasicMaterial({
    vertexColors: true,
  })

  const cX = []
  const cY = []
  const pointsX = []
  const pointsY = []
  const centerColor = new THREE.Color("rgb(224, 251, 171)")
  const lineColor = new THREE.Color("rgb(100, 100, 100)")
  
  for (let x = 0; x <= maxXCoord; x += 100) {
    pointsX.push(new THREE.Vector3(x, 0, minYCoord))
    pointsX.push(new THREE.Vector3(x, 0, maxYCoord))
    if (x === 0) {
      cX.push(centerColor.r, centerColor.g, centerColor.b)
      cX.push(centerColor.r, centerColor.g, centerColor.b)
    } else {
      cX.push(lineColor.r, lineColor.g, lineColor.b)
      cX.push(lineColor.r, lineColor.g, lineColor.b)
    }
  }

  for (let x = 0; x >= minXCoord; x -= 100) {
    pointsX.push(new THREE.Vector3(x, 0, minYCoord))
    pointsX.push(new THREE.Vector3(x, 0, maxYCoord))
    if (x === 0) {
      cX.push(centerColor.r, centerColor.g, centerColor.b)
      cX.push(centerColor.r, centerColor.g, centerColor.b)
    } else {
      cX.push(lineColor.r, lineColor.g, lineColor.b)
      cX.push(lineColor.r, lineColor.g, lineColor.b)
    }
  }

  for (let y = 0; y <= maxYCoord; y += 100) {
    pointsY.push(new THREE.Vector3(minXCoord, 0, y))
    pointsY.push(new THREE.Vector3(maxXCoord, 0, y))
    if (y === 0) {
      cY.push(centerColor.r, centerColor.g, centerColor.b)
      cY.push(centerColor.r, centerColor.g, centerColor.b)
    } else {
      cY.push(lineColor.r, lineColor.g, lineColor.b)
      cY.push(lineColor.r, lineColor.g, lineColor.b)
    }
  }

  for (let y = 0; y >= maxYCoord; y -= 100) {
    pointsY.push(new THREE.Vector3(minXCoord, 0, y))
    pointsY.push(new THREE.Vector3(maxXCoord, 0, y))
    if (y === 0) {
      cY.push(centerColor.r, centerColor.g, centerColor.b)
      cY.push(centerColor.r, centerColor.g, centerColor.b)
    } else {
      cY.push(lineColor.r, lineColor.g, lineColor.b)
      cY.push(lineColor.r, lineColor.g, lineColor.b)
    }
  }

  const xGeo = new THREE.BufferGeometry().setFromPoints(pointsX)
  xGeo.setAttribute("color", new THREE.Float32BufferAttribute(cX, 3))
  const xLine = new THREE.LineSegments(xGeo, material)

  const yGeo = new THREE.BufferGeometry().setFromPoints(pointsY)
  yGeo.setAttribute("color", new THREE.Float32BufferAttribute(cY, 3))
  const yLine = new THREE.LineSegments(yGeo, material)
  xLine.removeFromParent()
  yLine.removeFromParent()
  scene.add(xLine)
  scene.add(yLine)
}

const onKeyPress = (event: KeyboardEvent) => {
  if (event.code === "Space") {
    controls.screenSpacePanning = !controls.screenSpacePanning
    controls.update()
  }
}

/*const onDocumentMouseMove = (event: MouseEvent) => {
  event.preventDefault()
  mouse.x = (event.clientX / window.innerWidth) * 2 - 1
  mouse.y = -(event.clientY / window.innerHeight) * 2 + 1

  raycaster.setFromCamera(mouse, camera)

  const intersects = raycaster.intersectObject(points)
  const geometry = points.geometry as THREE.BufferGeometry
  const attributes = geometry.attributes
  if (intersects.length) {
    if (intersects[0].object.type === "Points") {
      //console.log(intersects[0].index, geometry.userData.names)
      if (INTERSECTED !== intersects[0].index) {
        ring.removeFromParent()
        document.querySelector("div.info")!.innerHTML = ""
        INTERSECTED = intersects[0].index
        const systemPosition = {
          // @ts-ignore
          x: attributes.position.array[INTERSECTED * 3],
          // @ts-ignore
          y: attributes.position.array[INTERSECTED * 3 + 1],
          // @ts-ignore
          z: attributes.position.array[INTERSECTED * 3 + 2],
        }
        const name = geometry.userData.names[INTERSECTED]
        const position = geometry.userData.positions[INTERSECTED]
        ring.position.set(systemPosition.x, systemPosition.y, systemPosition.z)
        /*document.querySelector("div.info")!.innerHTML = `
          <div class="text-2xl text-neutral-100"><a href="/system/${name}/">${name}</a></div>
          <div class="text-neutral-100">${position}</div>
        `*/
/*        scene.add(ring)
      }
    }
  } else if (INTERSECTED !== null) {
    INTERSECTED = null
    ring.removeFromParent()
  }
}*/

/*const onDocumentClick = (event: MouseEvent) => {
  event.preventDefault()
  mouse.x = (event.clientX / window.innerWidth) * 2 - 1
  mouse.y = -(event.clientY / window.innerHeight) * 2 + 1

  raycaster.setFromCamera(mouse, camera)
  const intersects = raycaster.intersectObject(scene)
  const geo = geometry.value
  const attributes = geo.attributes
  if (intersects.length) {
    if (SELECTED !== intersects[0].index) {
      controls.enabled = false
      const worldDirection = camera.getWorldDirection(new THREE.Vector3())

      SELECTED = intersects[0].index
      const systemPosition = {
        // @ts-ignore
        x: attributes.position.array[SELECTED * 3],
        // @ts-ignore
        y: attributes.position.array[SELECTED * 3 + 1],
        // @ts-ignore
        z: attributes.position.array[SELECTED * 3 + 2],
      }
      gsap.to(controls.target, {
        duration: 2,
        x: systemPosition.x,
        y: systemPosition.y,
        z: systemPosition.z,
        onUpdate: () => {
          controls.update()
        },
        onComplete: () => {
          controls.enabled = true
        },
      })

      gsap.to(camera.position, {
        duration: 2,
        x: systemPosition.x - worldDirection.x * 50,
        y: systemPosition.y - worldDirection.y * 50,
        z: systemPosition.z - worldDirection.z * 50,
        onUpdate: () => {
          controls.update()
          camera.updateProjectionMatrix()
        },
      })

      const name = geometry.value.userData.names[SELECTED]
      const position = geometry.value.userData.positions[SELECTED]
      /*document.querySelector("div.info")!.innerHTML = `
    <div class="text-2xl text-neutral-100"><a href="/system/${name}/">${name}</a></div>
    <div class="text-neutral-100">${position}</div>
    `*/
    /*}
  } else if (SELECTED !== null) {
    SELECTED = null
  }
}*/

const onWindowResize = () => {
  // Update camera
  camera.aspect = sizes.width / sizes.height
  camera.updateProjectionMatrix()

  // Update renderer
  renderer.setSize(sizes.width, sizes.height)
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2))
}

const animate = () => {
  requestAnimationFrame(animate)
  controls.update()
  ring.quaternion.copy(camera.quaternion)
  stats.update()
  render()
}

const render = () => {
  camera.updateProjectionMatrix()
  stats.begin()
  renderer.render(scene, camera)
  stats.end()
}
</script>
<style lang="scss">
.stats {
  @apply fixed bottom-0 left-0;
}

.system {
  @apply p-2;
  &:hover {
    @apply cursor-pointer bg-neutral-800;
  }
}

.loader {
  @apply absolute top-5 left-5;
  z-index: 10;
}
</style>
