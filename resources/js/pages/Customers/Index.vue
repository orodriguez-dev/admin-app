<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { index, create, destroy, edit } from '@/routes/customers';
import { Customer, type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { toast } from 'vue-sonner'
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow, } from '@/components/ui/table';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { MoreVerticalIcon, Plus, Eye, Pencil, Trash2Icon } from 'lucide-vue-next';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog';
import { ref } from 'vue';
import { useCustomerDelete } from './Composables/useCustomerDelete';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Clientes',
    href: index().url,
  },
];

const props = defineProps<{ customers: Customer[] }>();

const { showDialog, confirmDelete, deleteCustomer } = useCustomerDelete((id: number) => destroy(id).url);
</script>

<template>

  <Head title="Clientes" />

  <AppLayout :breadcrumbs="breadcrumbs">

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="flex flex-wrap items-center gap-2 md:flex-row">
        <Button as-child variant="ghost">
          <Link :href=create() class="inline-flex items-center">
          <Plus /> Nuevo
          </Link>
        </Button>
      </div>
      <div class="rounded-xl overflow-hidden border">
        <Table>
          <TableHeader class="bg-gray-100">
            <TableRow>
              <TableHead class="text-black font-bold">ID</TableHead>
              <TableHead class="text-black font-bold">Identification Type</TableHead>
              <TableHead class="text-black font-bold">Identification Number</TableHead>
              <TableHead class="text-black font-bold">Name</TableHead>
              <TableHead class="text-black font-bold">Email</TableHead>
              <TableHead class="text-black font-bold">Phone</TableHead>
              <TableHead class="text-black font-bold">Mobile Phone</TableHead>
              <TableHead class="text-black font-bold text-right w-10"></TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="customer in customers" :key="customer.id" class="hover:bg-gray-50">
              <TableCell class="px-3 py-2">{{ customer.id }}</TableCell>
              <TableCell class="px-3 py-2">{{ customer.identification_type_label }}</TableCell>
              <TableCell class="px-3 py-2">{{ customer.identification_number }}</TableCell>
              <TableCell class="px-3 py-2">{{ customer.name }}</TableCell>
              <TableCell class="px-3 py-2">{{ customer.email }}</TableCell>
              <TableCell class="px-3 py-2">{{ customer.phone }}</TableCell>
              <TableCell class="px-3 py-2">{{ customer.mobile_phone }}</TableCell>
              <TableCell class="text-right px-3 py-0">
                <DropdownMenu>
                  <DropdownMenuTrigger as-child>
                    <Button variant="ghost" size="icon" aria-label="Opciones">
                      <MoreVerticalIcon />
                    </Button>
                  </DropdownMenuTrigger>
                  <DropdownMenuContent align="end" class="w-48">
                    <DropdownMenuItem>
                      <Eye />
                      Ver
                    </DropdownMenuItem>
                    <DropdownMenuItem as-child>
                      <Link :href="edit(customer)" class="flex items-center gap-2">
                        <Pencil /> Editar
                      </Link>
                    </DropdownMenuItem>
                    <DropdownMenuItem @click="confirmDelete(customer.id)"
                      class="text-red-600 focus:text-red-600 focus:bg-red-50">
                      <Trash2Icon class="text-red-600" />
                      Eliminar
                    </DropdownMenuItem>
                  </DropdownMenuContent>
                </DropdownMenu>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>
      <TableCaption>Lista de clientes.</TableCaption>

      <!-- Modal de confirmación de eliminación -->
      <Dialog v-model:open="showDialog">
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Eliminar Cliente</DialogTitle>
          </DialogHeader>
          <p>¿Estás seguro que deseas eliminar este cliente? Esta acción no se puede deshacer.</p>
          <DialogFooter class="flex justify-end gap-2">
            <Button variant="outline" @click="showDialog = false">Cancelar</Button>
            <Button variant="destructive" @click="deleteCustomer">Eliminar</Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  </AppLayout>
</template>