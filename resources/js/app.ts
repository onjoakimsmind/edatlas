import './bootstrap'
import '../css/app.css'

import { createApp, h, DefineComponent } from 'vue'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import resolveConfig from 'tailwindcss/resolveConfig'
import tailwindConfig from '../../tailwind.config.js'
import { useSettings } from './store/settings'
import * as configcat from 'configcat-js'
const fullConfig = resolveConfig(tailwindConfig)

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'ED Atlas'

const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)

const useLogging = true
const key = import.meta.env.VITE_CONFIGCAT_SDK

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob<DefineComponent>('./Pages/**/*.vue')
    ),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
    app.use(plugin)
    app.use(ZiggyVue, Ziggy)
    app.use(pinia)
    app.mount(el)

    const logger = useLogging ? configcat.createConsoleLogger(configcat.LogLevel.Warn) : null
    const configCatClient = configcat.getClient(key, configcat.PollingMode.AutoPoll, {
      logger: logger,
    })
    app.config.globalProperties.$configCat = configCatClient

    const settings = useSettings()
    if (localStorage.getItem('settings') === null) {
      settings.toggleDark(true)
    }
  },
  progress: {
    // @ts-ignore
    color: fullConfig.theme?.colors?.anzac[500],
  },
})
