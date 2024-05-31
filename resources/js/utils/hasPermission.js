import { usePage } from '@inertiajs/vue3'

export const hasPermission = (name) => {
    const { auth } = usePage().props;
    const userPermissions = auth.permissions || [];
    return userPermissions.some((p) => p.permission === name);
};