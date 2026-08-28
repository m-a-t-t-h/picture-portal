import { ref, onMounted } from 'vue';

export function useAuth() {
    const user = ref(null)
    const loading = ref(true)

    const checkAuth = async () => {
        try {
            const response = await fetch('/auth/user', {
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                },
            })

            if (response.ok) {
                const data = await response.json()
                user.value = data.user
            } else if (response.status === 401) {
                user.value = null
            }
        } finally {
            loading.value = false
        }
    }

    onMounted(checkAuth)

    return {
        user,
        loading,
        isAuthenticated: user,
        checkAuth,
    }
}
