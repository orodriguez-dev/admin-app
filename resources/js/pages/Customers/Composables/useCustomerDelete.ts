import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

export function useCustomerDelete(deleteRoute: (id: number) => string) {
  const showDialog = ref(false);
  const deletingId = ref<number | null>(null);

  const confirmDelete = (id: number) => {
    deletingId.value = id;
    showDialog.value = true;
  };

  const deleteCustomer = () => {
    if (!deletingId.value) return;

    router.delete(deleteRoute(deletingId.value), {
      preserveScroll: true,
      onSuccess: () => toast.success('Cliente eliminado correctamente'),
      onError: () => toast.error('Error al eliminar cliente'),
      onFinish: () => {
        showDialog.value = false;
        deletingId.value = null;
      },
    });
  };

  return { showDialog, deletingId, confirmDelete, deleteCustomer };
}