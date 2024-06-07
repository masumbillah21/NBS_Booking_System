<script setup>
    import FormFilePicker from '@/Components/Forms/FormFilePicker.vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';
    import { useForm, usePage } from '@inertiajs/vue3';

    const generalInfo = usePage().props.auth.profile;
    const storeUrl = usePage().props.urls.storeUrl

    const form = useForm({
        photo: '',
        phone: '',
        address: '',
        city: '',
        zip_code: '',
        country: '',
        gender: '',
    });

    if(generalInfo){
        form.phone = generalInfo.phone
        form.address = generalInfo.address
        form.city = generalInfo.city
        form.zip_code = generalInfo.zip_code
        form.country = generalInfo.country
        form.gender = generalInfo.gender
    }
    
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">General Information</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your account's general information.
            </p>
        </header>

        <form @submit.prevent="form.post(route('profile.store'))" class="mt-6 space-y-6">

            <div class="mb-4">
                <InputLabel class="font-medium text-black dark:text-white" value="Profile Photo" />
                <img class="rounded-full" v-if="generalInfo && generalInfo.photo" :src="storeUrl + generalInfo.photo" alt="" width="250">

                <FormFilePicker label="Upload Photo" v-model="form.photo" />
                <InputError class="mt-2" :message="form.errors.photo" />
            </div>
            <div>
                <InputLabel for="phone-number" value="Phone Number" />

                <TextInput id="phone-number" type="tel" class="mt-1 block w-full" v-model="form.phone" required
                    autofocus />

                <InputError class="mt-2" :message="form.errors.phone" />
            </div>

            <div>
                <InputLabel for="gender" value="Gender" />

                <TextInput id="gender" type="tel" class="mt-1 block w-full" v-model="form.gender" required
                    autofocus />

                <InputError class="mt-2" :message="form.errors.gender" />
            </div>

            <div>
                <InputLabel for="address" value="Address" />

                <TextInput id="address" type="text" class="mt-1 block w-full" v-model="form.address" required
                    autofocus />

                <InputError class="mt-2" :message="form.errors.address" />
            </div>

            <div>
                <InputLabel for="city" value="City" />

                <TextInput id="city" type="text" class="mt-1 block w-full" v-model="form.city" required
                    autofocus />

                <InputError class="mt-2" :message="form.errors.city" />
            </div>

            <div>
                <InputLabel for="zip-code" value="Zip Code" />

                <TextInput id="zip-code" type="text" class="mt-1 block w-full" v-model="form.zip_code" required
                    autofocus />

                <InputError class="mt-2" :message="form.errors.zip_code" />
            </div>

            <div>
                <InputLabel for="country" value="Country" />

                <TextInput id="country" type="text" class="mt-1 block w-full" v-model="form.country" required
                    autofocus />

                <InputError class="mt-2" :message="form.errors.country" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
