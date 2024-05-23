<script setup lang="ts">
    import { computed, ref, reactive } from 'vue'
    import SectionMain from '@/Components/SectionMain.vue'
    import CardBox from '@/Components/CardBox.vue'
    import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
    import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
    import BaseButtonLink from '@/Components/BaseButtonLink.vue'
    import CardBoxModal from '@/Components/CardBoxModal.vue'
    import FormSuccess from "@/Components/FormSuccess.vue";
    import FormValidationErrors from "@/Components/FormValidationErrors.vue";
    import { hasPermission } from '@/utils/hasPermission.js'
    import Vue3Datatable from '@bhplugin/vue3-datatable'
    import FormControl from '@/Components/FormControl.vue'
    import '@bhplugin/vue3-datatable/dist/style.css'
    import { Head, useForm, usePage } from '@inertiajs/vue3'

    const rolesData: any = usePage().props.rolesData

    const form: any = useForm({
        id: 0,
        _method: 'delete'
    })

    const isModalDangerActive = ref(false)
    const isOpen = ref(false);
    
    const deleteRole = async () => {
        isModalDangerActive.value = false
        form.delete(route('admin.roles.destroy', form.id),{
            onSuccess: () => {
              const index = rows.value.findIndex((role: any) => role.id === form.id)
              if (index !== -1) {
                  rows.value.splice(index, 1)
                  rows.value = [...rows.value]
              }
            }
        })
    }
    const showModle = (id : string | number) => {
        isModalDangerActive.value = true
        form.id = id
    }

    const params = reactive({
        current_page: 1,
        pagesize: 10,
        sort_column: 'id',
        sort_direction: 'asc',
        search: '',
    });

    const cols = ref([
      {title: 'SL', field: 'sl', isUnique: true, type: 'number', width: '40px', hide:false},
      {title: 'Name', field: 'name', width: '200px', hide:false},
      {title: 'Permissions', field: 'permissions', hide:false},
      {title: 'Created', field: 'created_at', width: '40px', hide:false},
      {title: 'Updated', field: 'updated_at', width: '40px', hide:false},
      {title: 'Action', field: 'action', hide:false},
    ])

    const rows = ref(rolesData.map((role: any, index: number) => {
        return {
          sl: index + 1,
          id: role.id,
          name: role.name,
          permissions: role.permissions.map((permission: any) => permission.name).join(', '),
          created_at: role.created_at,
          updated_at: role.updated_at,
        }
    }))

    const filterdJobList = computed(() => {
      if(!params.search) return rolesData.slice(0, params.pagesize);
      const query = params.search.toLowerCase();
      return rolesData?.filter((item: any) => item.name.toLowerCase().includes(query));
    })
</script>
    
    <template>
      <LayoutAuthenticated>
        <Head title="Roles" />
        <SectionMain>
          <SectionTitleLineWithButton icon="fas fa-arrow-alt-circle-right" title="Roles" main>
            <BaseButtonLink v-if="hasPermission('role.create')"
              icon="fas fa-plus"
              routeName="roles.create"
              label="Add New"
              color="contrast"
              rounded-full
              small
            />
          </SectionTitleLineWithButton>
          
          <CardBoxModal v-model="isModalDangerActive" title="Warning" button="danger" :onConfirm="deleteRole" buttonLabel="Delete" has-cancel>
            <p>Do you really want to delete?</p>
          </CardBoxModal>
          <CardBox class="mb-6 relative overflow-x-auto shadow-md sm:rounded-lg" has-table>
            <FormValidationErrors/>
            <FormSuccess class="pt-5 pl-5" />
            <div class="flex justify-between px-3 pt-4">
                <div class="mb-5 relative">
                  <button type="button" class="bg-slate-800 text-white p-2 inline-block rounded" @click="isOpen = !isOpen">
                      Column Compose <i :class="isOpen ? 'fa fa-chevron-up' : 'fa fa-chevron-down'" aria-hidden="true"></i>
                  </button>
                  <ul v-if="isOpen" class="absolute left-0 mt-0.5 p-2.5 min-w-[150px] bg-white rounded shadow-md space-y-1 z-10">
                      <li v-for="col in cols" :key="col.field">
                          <label class="flex items-center gap-2 w-full cursor-pointer text-gray-600 hover:text-black">
                              <input type="checkbox" class="form-checkbox" :checked="!col.hide" @change="col.hide = !($event.target as HTMLInputElement)?.checked" />
                              <span>{{ col.title }}</span>
                          </label>
                      </li>
                  </ul>
                </div>
                <FormControl  v-model="params.search" type="text" placeholder="Search..." />
            </div>
            <Vue3Datatable 
              :rows="rows" 
              :columns="cols" 
              :sortable="true"
              :sortColumn="params.sort_column"
              :sortDirection="params.sort_direction"
              :search="params.search"
              :columnFilter="true"
              @change="filterdJobList"
              :cloneHeaderInFooter="true"
              skin="bh-table-compact"
              class="column-filter p-4"
              rowClass="bg-white dark:bg-slate-800 dark:text-slate-300 dark:border-gray-600"
            >
            <template #action="data">
              <template class="flex">
                <BaseButtonLink v-if="hasPermission('role.update')" routeName="roles.edit" :routeParams="data.value.id" icon="fas fa-edit" label="Edit" color="info" small />
                <BaseButtonLink v-if="hasPermission('role.delete')" class="ml-2" icon="fas fa-trash-alt" label="Delete" color="danger" small @click="showModle(data.value.id)"/>
              </template>
            </template>
            </Vue3Datatable>
          </CardBox>
        </SectionMain>
      </LayoutAuthenticated>
    </template>