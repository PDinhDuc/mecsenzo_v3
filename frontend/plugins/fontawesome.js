import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// Import các icon bạn cần
import { faUser, faMagnifyingGlass, faBell, faCaretDown,
  faReply, faEllipsisVertical, faVideo, faUserPlus,
  faCircleInfo
} from '@fortawesome/free-solid-svg-icons'

// Thêm vào thư viện
library.add(faUser, faMagnifyingGlass, faBell, faCaretDown,
  faReply, faEllipsisVertical, faVideo, faUserPlus, faCircleInfo)

export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.vueApp.component('font-awesome-icon', FontAwesomeIcon)
})
