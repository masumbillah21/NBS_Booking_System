<script setup lang="ts">
import { computed, ref, reactive } from 'vue'
import SectionMain from '@/Components/SectionMain.vue'
import CardBox from '@/Components/CardBox.vue'
import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";
import FormCheckRadioGroup from "@/Components/FormCheckRadioGroup.vue";
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import BaseButtonLink from '@/Components/BaseButtonLink.vue'
import BaseButtons from "@/Components/BaseButtons.vue";
import BaseDivider from "@/Components/BaseDivider.vue";
import FormValidationErrors from "@/Components/FormValidationErrors.vue";
import FormSuccess from "@/Components/FormSuccess.vue";
import { Head, useForm, usePage } from '@inertiajs/vue3'

const roleData: any = usePage().props.role ?? null
const permissionList: any = usePage().props.permissionList ?? null


const form: any = useForm({
    id: 0,
    name: "",
    permissions: [],
    _method: "post",
});


if (roleData !== null) {
    form.id = roleData.id
    form.name = roleData.name
    form.permissions = roleData.permissions.map((permission: any) => permission.id);
    form._method = 'put'
}

const submit = () => {
    if (roleData !== null) {
        update();
    } else {
        create();
    }
};


const create = () => {
    form.post(route("admin.roles.store"), {
        onSuccess: () => form.reset(),
    });
};


const update = () => {
    console.log(form)
    form.post(route("admin.roles.update", form.id));
};
</script>
    
<template>
    <LayoutAuthenticated>

        <Head :title="(roleData !== null) ? 'Edit Role' : 'Create Role'" />
        <SectionMain>
            <SectionTitleLineWithButton icon="far fa-arrow-alt-circle-right"
                :title="roleData !== null ? 'Edit Role' : 'Create Role'" main>
                <BaseButtonLink icon="far fa-arrow-alt-circle-left" label="Back" routeName="roles.index" color="contrast"
                    rounded-full small />
            </SectionTitleLineWithButton>
            <div class="flex justify-center items-center">
                <CardBox class="my-24 w-9/12" is-form @submit.prevent="submit">
                    <FormValidationErrors />
                    <FormSuccess />
                    <FormField label="Name" label-for="name" help="Please enter role name">
                        <FormControl v-model="form.name" id="name" autocomplete="name" type="text" required />
                    </FormField>

                    <FormField label="Permissions" label-for="permissions" help="Please select permissions">
                        <FormCheckRadioGroup v-model="form.permissions" name="permissions" :options="permissionList" />
                    </FormField>
                    <BaseDivider />

                    <BaseButtons>
                        <BaseButtonLink type="submit" color="info" :label="roleData !== null ? 'Update' : 'Create'"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
                    </BaseButtons>
                </CardBox>
            </div>

        </SectionMain>
    </LayoutAuthenticated>
</template>
