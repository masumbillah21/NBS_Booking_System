<script setup lang='ts'>
import { ref } from 'vue'
import { Head, router, usePage, useForm } from '@inertiajs/vue3'
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import FormFilePicker from '@/Components/Forms/FormFilePicker.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectGroup from '@/Components/Forms/SelectGroup.vue';

const roles: any = usePage().props.roles
const general: any = usePage().props.general
const urls: any = usePage().props.urls

const form: any = useForm({
    tab_name: 'general',
    site_title: '',
    stie_tagline: '',
    dark_dark_logo: '',
    light_dark_logo: '',
    site_favicon: '',
    default_role: '',
    date_format: 'dd-mm-yyyy',
    _method: 'post'
})

const dark_logo = ref(null)
const light_logo = ref(null)

const favicon = ref(null)

if (general !== null) {
    general.forEach(item => {
        if(item.name === 'dark_logo'){ 
            dark_logo.value = item.value
        }else if(item.name === 'light_logo'){
            light_logo.value = item.value
        }else if(item.name === 'site_favicon'){
            favicon.value = item.value
        }else{
            form[item.name] = item.value
        }
    });
}



const save = () => {
    form.post(route('settings.store'))
}


</script>

<template>
    <form @submit.prevent="save">
        <div class="mb-4">
            <InputLabel class="font-medium text-black dark:text-white" for="site-title" value="Site Title" />
            <TextInput
                id="site-title"
                type="text"
                class="mt-1 block w-full"
                v-model="form.site_title"
                required
                autofocus
                autocomplete="site_title"
            />
            <InputError class="mt-2" :message="form.errors.site_title" />
        </div>
        <div class="mb-4">
            <InputLabel class="font-medium text-black dark:text-white" for="stie-tagline" value="Stie Tagline" />
            <TextInput
                id="stie-tagline"
                type="text"
                class="mt-1 block w-full"
                v-model="form.stie_tagline"
                required
                autocomplete="username"
            />
            <InputError class="mt-2" :message="form.errors.stie_tagline" />
        </div>
        <div class="mb-4">
            <InputLabel class="font-medium text-black dark:text-white" for="dark-logo" value="Dark Logo" />
            <img v-if="dark_logo" :src="urls.storeUrl + dark_logo" alt="" width="250">
            
            <FormFilePicker label="Upload Dark Logo" v-model="form.dark_dark_logo"/>
            <InputError class="mt-2" :message="form.errors.dark_dark_logo" />
        </div>
        <div class="mb-4">
            <InputLabel class="font-medium text-black dark:text-white" for="light-logo" value="Light Logo" />
            <img v-if="light_logo" :src="urls.storeUrl + light_logo" alt="" width="250">
            
            <FormFilePicker label="Upload Light Logo" v-model="form.light_logo"/>
            <InputError class="mt-2" :message="form.errors.light_logo" />
        </div>
        <div class="mb-4">
            <InputLabel class="font-medium text-black dark:text-white" for="favicon" value="Favicon" />
            <img v-if="favicon" :src="urls.storeUrl + favicon" alt="" width="200">
            
            <FormFilePicker label="Upload Favicon" v-model="form.site_favicon"/>
            <InputError class="mt-2" :message="form.errors.site_favicon" />
        </div>
        <div class="mb-4">
            <SelectGroup class="font-medium text-black dark:text-white" label="Default Role" v-model="form.default_role" :options="roles"/>
            <InputError class="mt-2" :message="form.errors.default_role" />
        </div>
        <div class="mb-4">
            <InputLabel class="font-medium text-black dark:text-white" for="date-format" value="Date Format" />
            <TextInput
                id="date-format"
                type="text"
                class="mt-1 block w-full"
                v-model="form.date_format"
                required
            />
            <InputError class="mt-2" :message="form.errors.date_format" />
        </div>
        <div class="flex items-center gap-4">
            <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
        </div>

    </form>

</template>