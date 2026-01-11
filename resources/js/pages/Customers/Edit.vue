<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { index, edit, update } from '@/routes/customers';
import { type BreadcrumbItem, Customer } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Spinner } from '@/components/ui/spinner';
import { toast } from 'vue-sonner';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/components/ui/select';
import { useCustomerForm } from './Composables/useCustomerForm';

interface Props {
    customer: Customer,
    identificationTypes: { value: string; label: string }[],
}

const props = defineProps<{ customer: Customer; identificationTypes: { value: string; label: string }[] }>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Clientes',
        href: index().url,
    },
    {
        title: 'Editar',
        href: edit(props.customer).url,
    }
];

const { form, submit } = useCustomerForm(props.customer, update(props.customer).url, 'put');
</script>

<template>

    <Head title="Editar Cliente" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <Card class="w-full max-w-4xl mx-auto">
                <CardHeader>
                    <CardTitle>Ficha Cliente</CardTitle>
                </CardHeader>

                <CardContent>
                    <form @submit.prevent="submit" class="space-y-4">
                        <!-- 2 columnas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Tipo Identificación -->
                            <div class="flex flex-col space-y-1.5">
                                <Label for="identification_type">Tipo Identificación</Label>
                                <Select id="identification_type" name="identification_type" v-model="form.identification_type">
                                    <SelectTrigger class="w-full h-9">
                                        <SelectValue placeholder="Seleccionar" />
                                    </SelectTrigger>
                                    <SelectContent >
                                        <SelectItem v-for="type in identificationTypes" :key="type.value" :value="type.value">
                                            {{ type.label }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>

                                <InputError :message="form.errors.identification_type" class="mt-2" />
                            </div>
                            <!-- Número Identificación -->
                            <div class="flex flex-col space-y-1.5">
                                <Label for="identification_number">N° Identificación</Label>
                                <Input id="identification_number" name="identification_number" type="text"
                                    placeholder="N° Identificación" v-model="form.identification_number"/>
                                <InputError :message="form.errors.identification_number" class="mt-2" />
                            </div>
                        </div>
                        <!-- Campo Name (una sola columna) -->
                        <div class="grid gap-2 mt-4">
                            <Label for="name">Nombre Completo</Label>
                            <Input id="name" name="name" class="mt-1 block w-full" autocomplete="name"
                                placeholder="Nombre completo" v-model="form.name" />
                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>
                        <!-- Footer visual dentro del CardContent pero funcional -->
                        <div class="flex justify-between mt-6">
                            <Button as-child variant="outline">
                                <Link :href="index().url">Atrás</Link>
                            </Button>

                            <Button type="submit" :disabled="form.processing" class="inline-flex items-center gap-2">
                                <Spinner v-if="form.processing" />
                                Guardar
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>