import type { App } from 'vue'
import {
  Button,
  Icon,
  NavBar,
  Tabbar,
  TabbarItem,
  Cell,
  CellGroup,
  Image as VanImage,
  Lazyload,
  Loading,
  Toast,
  Dialog,
  Notify,
  PullRefresh,
  List,
  Skeleton,
  Swipe,
  SwipeItem,
  Rate,
  Tag,
  Stepper,
  Field,
  Form,
  Checkbox,
  CheckboxGroup,
  RadioGroup,
  Radio,
  Popup,
  DropdownMenu,
  DropdownItem,
  Empty,
} from 'vant'

// 导入Vant样式
import 'vant/lib/index.css'

// 需要注册的组件
const components = [
  Button,
  Icon,
  NavBar,
  Tabbar,
  TabbarItem,
  Cell,
  CellGroup,
  VanImage,
  Loading,
  PullRefresh,
  List,
  Skeleton,
  Swipe,
  SwipeItem,
  Rate,
  Tag,
  Stepper,
  Field,
  Form,
  Checkbox,
  CheckboxGroup,
  RadioGroup,
  Radio,
  Popup,
  DropdownMenu,
  DropdownItem,
  Empty,
]

// Vant组件注册
export function setupVant(app: App<Element>): void {
  components.forEach((component) => {
    app.use(component)
  })

  // 注册指令，使用相对路径
  app.use(Lazyload, {
    lazyComponent: true,
    loading: '/assets/images/loading-placeholder.png',
    error: '/assets/images/error-placeholder.png',
  })

  // 挂载到全局
  app.config.globalProperties.$toast = Toast
  app.config.globalProperties.$dialog = Dialog
  app.config.globalProperties.$notify = Notify
}

export { Toast, Dialog, Notify }
