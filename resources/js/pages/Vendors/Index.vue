<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { index, create, destroy, edit } from '@/routes/vendors';
import { Vendor, type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { toast } from 'vue-sonner'
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow, } from '@/components/ui/table';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { MoreVerticalIcon, Plus, Eye, Pencil, Trash2Icon } from 'lucide-vue-next';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '@/components/ui/dialog';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Proveedores',
    href: index().url,
  },
];

interface Props {
  vendors: Vendor[],
}

const{ vendors } = defineProps<Props>();

// Modal de eliminación
const showDeleteDialog = ref(false);
const deletingVendorId = ref<number | null>(null);

const confirmDelete = (id: number) => {
  deletingVendorId.value = id;
  showDeleteDialog.value = true;
};

const deleteVendor = () => {
  if (deletingVendorId.value === null) return;

  router.delete(destroy(deletingVendorId.value).url, {
    preserveScroll: true,
    onSuccess: () => toast.success('Proveedor eliminado correctamente'),
    onError: () => toast.error('Error al eliminar el proveedor'),
    onFinish: () => {
      showDeleteDialog.value = false;
      deletingVendorId.value = null;
    },
  });
};
</script>

<template>

  <Head title="Proveedores" />

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
              <TableHead class="text-black font-bold">Nombre</TableHead>
              <TableHead class="text-black font-bold">Contacto</TableHead>
              <TableHead class="text-black font-bold">Correo</TableHead>
              <TableHead class="text-black font-bold">Telefono</TableHead>
              <TableHead class="text-black font-bold">Dirección</TableHead>
              <TableHead class="text-black font-bold text-right w-10"></TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="vendor in vendors" :key="vendor.id" class="hover:bg-gray-50">
              <TableCell class="px-3 py-2">{{ vendor.id }}</TableCell>
              <TableCell class="px-3 py-2">{{ vendor.name }}</TableCell>
              <TableCell class="px-3 py-2">{{ vendor.contact_name }}</TableCell>
              <TableCell class="px-3 py-2">{{ vendor.email }}</TableCell>
              <TableCell class="px-3 py-2">{{ vendor.phone }}</TableCell>
              <TableCell class="px-3 py-2">{{ vendor.address }}</TableCell>
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
                      <Link :href="edit(vendor)" class="flex items-center gap-2">
                        <Pencil /> Editar
                      </Link>
                    </DropdownMenuItem>
                    <DropdownMenuItem @click="confirmDelete(vendor.id)"
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
      <TableCaption>Lista de proveedores.</TableCaption>

      <!-- Modal de confirmación de eliminación -->
      <Dialog v-model:open="showDeleteDialog">
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Eliminar Proveedor</DialogTitle>
          </DialogHeader>
          <p>¿Estás seguro que deseas eliminar este proveedor? Esta acción no se puede deshacer.</p>
          <DialogFooter class="flex justify-end gap-2">
            <Button variant="outline" @click="showDeleteDialog = false">Cancelar</Button>
            <Button variant="destructive" @click="deleteVendor">Eliminar</Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  </AppLayout>
</template>