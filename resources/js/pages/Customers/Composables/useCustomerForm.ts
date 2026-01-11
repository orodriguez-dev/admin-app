import { useForm } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

export function useCustomerForm(initialData: any, routeUrl: string, method: 'post' | 'put' = 'post') {
  const form = useForm({ ...initialData });

  const submit = () => {
    if (method === 'post') {
      form.post(routeUrl, {
        preserveScroll: true,
        onSuccess: () => toast.success('Cliente creado correctamente'),
        onError: () => toast.error('Error al crear cliente'),
      });
    } else {
      form.put(routeUrl, {
        preserveScroll: true,
        onSuccess: () => toast.success('Cliente actualizado correctamente'),
        onError: () => toast.error('Error al actualizar cliente'),
      });
    }
  };

  return { form, submit };
}