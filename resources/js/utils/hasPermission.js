import { usePage } from '@inertiajs/vue3'

export const hasPermission = (name) => {
    const { auth } = usePage().props;
    const userPermissions = auth.permissions || [];
    console.log("permission:" + userPermissions)
    return userPermissions.some((p) => p.permission === name);
};