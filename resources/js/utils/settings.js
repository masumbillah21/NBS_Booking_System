import { usePage } from '@inertiajs/vue3'

export const getSettings = (name) => {
    const settings = usePage().props.settings;
    let item = settings.find((p) => p.name === name);

    return item ? item.value : ''
};