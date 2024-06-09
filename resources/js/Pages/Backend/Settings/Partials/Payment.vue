<script setup lang='ts'>
import { ref, watch } from 'vue'
import { Head, router, usePage, useForm } from '@inertiajs/vue3'
import SwitchThree from '@/Components/Forms/Switchers/SwitchThree.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const payments: any = usePage().props.payment

const mode = ref(false)

const form = useForm({
    tab_name: 'payment',
    payment_mode: '',
    test_pk: '',
    test_sk: '',
    live_pk: '',
    live_sk: '',
    service_charge: '',
    _method: 'post'
})

if (payments !== null) {
    payments.forEach(item => {
        if(item.name === 'payment_mode'){
            mode.value = item.value === 'live' ? true : false
            form.payment_mode = item.value
        }else{
            form[item.name] = item.value
        }
    });

}
const save = () => {
    form.post(route('settings.store'))
}

watch(() => mode.value, (newValue) => {
  form.payment_mode = newValue ? 'live' : 'test'
})

</script>

<template>
    <form @submit.prevent="save">
        <div class="mb-4">
            <InputLabel class="font-medium text-black dark:text-white" for="mode" value="Is Live?" />
            <SwitchThree class="mt-3" v-model="mode" />
            <InputError class="mt-2" :message="form.errors.payment_mode" />
        </div>
        <template v-if="!mode">
            <div class="mb-4">
                <InputLabel class="font-medium text-black dark:text-white" for="test-pk" value="Test Public Key" />
                <TextInput
                    id="test-pk"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.test_pk"
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.test_pk" />
            </div>
            <div class="mb-4">
                <InputLabel class="font-medium text-black dark:text-white" for="test-sk" value="Test Secret Key" />
                <TextInput
                    id="test-sk"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.test_sk"
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.test_sk" />
            </div>
        </template>

        <template v-else>
            <div class="mb-4">
                <InputLabel class="font-medium text-black dark:text-white" for="live-pk" value="Live Public Key" />
                <TextInput
                    id="live-pk"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.live_pk"
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.live_pk" />
            </div>
            <div class="mb-4">
                <InputLabel class="font-medium text-black dark:text-white" for="live-sk" value="Live Secret Key" />
                <TextInput
                    id="live-sk"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.live_sk"
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.live_sk" />
            </div>
        </template>

        <div class="mb-4">
            <InputLabel class="font-medium text-black dark:text-white" for="service-charge" value="Service Charge" />
            <TextInput
                id="service-charge"
                type="text"
                class="mt-1 block w-full"
                v-model="form.service_charge"
                autofocus
            />
            <InputError class="mt-2" :message="form.errors.service_charge" />
        </div>

        <div class="flex items-center gap-4">
            <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
        </div>

    </form>

</template>