import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import { watch } from 'vue'
import 'dayjs/locale/vi'
import 'dayjs/locale/ja'
import 'dayjs/locale/en'

dayjs.extend(relativeTime)
dayjs.locale('en')

export default function useTimeAgo(date) {
  const { locale } = useI18n()
  dayjs.locale(locale.value)
  watch(locale, (newLocale) => {
  dayjs.locale(newLocale)
})
  return dayjs(date).fromNow()
}
