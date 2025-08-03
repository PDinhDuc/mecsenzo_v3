export default function realtimeUserActive(updateUserStatus) {
  const { $echo } = useNuxtApp()

  const listen = () => {
    $echo.private('conversation-user-status')
      .listen('UserOnlineStatusUpdated', (event) => {
        console.log(event);
        
        const { user_id, is_online } = event
        updateUserStatus(user_id, is_online)
      })
  }

  const leave = () => {
    $echo.leave('UserOnlineStatusUpdated')
  }

  return {
    listen,
    leave,
  }
}
