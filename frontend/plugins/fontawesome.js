import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// Import các icon bạn cần
import { faUser, faMagnifyingGlass, faBell, faCaretDown, faReply } from '@fortawesome/free-solid-svg-icons'

// Thêm vào thư viện
library.add(faUser, faMagnifyingGlass, faBell, faCaretDown, faReply)

export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.vueApp.component('font-awesome-icon', FontAwesomeIcon)
})
