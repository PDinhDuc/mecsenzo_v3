export default function realtimeUserActive(updateUserStatus) {
  const { $echo } = useNuxtApp()

  const listen = () => {
    $echo.private('conversation-user-status')
      .listen('UserOnlineStatusUpdated', (event) => {
        console.log(event);
        
        const { id, name, is_online } = event
        updateUserStatus(id, name, is_online)
      })
  }

  const leave = () => {
    $echo.leave('conversation-user-status')
  }

  return {
    listen,
    leave,
  }
}
