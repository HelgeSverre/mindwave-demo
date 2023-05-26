import Alpine from "alpinejs"
import intersect from '@alpinejs/intersect'
import focus from '@alpinejs/focus'
import ToastComponent from '../../vendor/usernotnull/tall-toasts/resources/js/tall-toasts'


Alpine.data('ToastComponent', ToastComponent)
Alpine.plugin(focus)
Alpine.plugin(intersect)

window.Alpine = Alpine
Alpine.start()
