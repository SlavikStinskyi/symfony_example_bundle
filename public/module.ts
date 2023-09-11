import { RouteConfig } from 'vue-router'

import Module from '@/admin-bundle/module'
import MobileAppSettingsList from "./settings/list/index.vue";
import MobileAppSettings from "./settings/view/index.vue";

const routes: RouteConfig[] = [
  { path: '/admin/mobile/settings', name: 'mobile-app-settings', component: MobileAppSettingsList },
  { path: '/admin/mobile/settings/create', name: 'mobile-app-settings-create', component: MobileAppSettings },
  { path: '/admin/mobile/settings/:id', name: 'mobile-app-settings-edit', component: MobileAppSettings }
]

export default new Module('mobile-app-settings', routes)
