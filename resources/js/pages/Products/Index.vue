<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { index, create, destroy, edit } from '@/routes/products';
import { Product, type BreadcrumbItem } from '@/types';
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
    title: 'Productos',
    href: index().url,
  },
];

interface Props {
  products: Product[],
}

const{ products} = defineProps<Props>();

// Modal de eliminación
const showDeleteDialog = ref(false);
const deletingProductId = ref<number | null>(null);

const confirmDelete = (id: number) => {
  deletingProductId.value = id;
  showDeleteDialog.value = true;
};

const deleteProduct = () => {
  if (deletingProductId.value === null) return;

  router.delete(destroy(deletingProductId.value).url, {
    preserveScroll: true,
    onSuccess: () => toast.success('Producto eliminado correctamente'),
    onError: () => toast.error('Error al eliminar el producto'),
    onFinish: () => {
      showDeleteDialog.value = false;
      deletingProductId.value = null;
    },
  });
};
</script>

<template>

  <Head title="Productos" />

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
              <TableHead class="text-black font-bold">Código</TableHead>
              <TableHead class="text-black font-bold">Nombre</TableHead>
              <TableHead class="text-black font-bold">Descripción</TableHead>
              <TableHead class="text-black font-bold">Precio</TableHead>
              <TableHead class="text-black font-bold">Stock</TableHead>
              <TableHead class="text-black font-bold text-right w-10"></TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="product in products" :key="product.id" class="hover:bg-gray-50">
              <TableCell class="px-3 py-2">{{ product.id }}</TableCell>
              <TableCell class="px-3 py-2">{{ product.sku }}</TableCell>
              <TableCell class="px-3 py-2">{{ product.name }}</TableCell>
              <TableCell class="px-3 py-2">{{ product.description }}</TableCell>
              <TableCell class="px-3 py-2">{{ product.price }}</TableCell>
              <TableCell class="px-3 py-2">{{ product.stock }}</TableCell>
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
                      <Link :href="edit(product)" class="flex items-center gap-2">
                        <Pencil /> Editar
                      </Link>
                    </DropdownMenuItem>
                    <DropdownMenuItem @click="confirmDelete(product.id)"
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
      <TableCaption>Lista de Productos.</TableCaption>

      <!-- Modal de confirmación de eliminación -->
      <Dialog v-model:open="showDeleteDialog">
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Eliminar Cliente</DialogTitle>
          </DialogHeader>
          <p>¿Estás seguro que deseas eliminar este cliente? Esta acción no se puede deshacer.</p>
          <DialogFooter class="flex justify-end gap-2">
            <Button variant="outline" @click="showDeleteDialog = false">Cancelar</Button>
            <Button variant="destructive" @click="deleteProduct">Eliminar</Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  </AppLayout>
</template>