import { usePage } from '@inertiajs/vue3'
import { UserTypeEnum } from '@/utils/userTypeEnum';

export const isSuperUser = () => {
    const { auth } = usePage().props;

    if (!auth.role) {
        return false;
    }
    const userRole = auth.role.name || '';
    
    return userRole === 'Super Admin';
};