<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { index, calculate } from '@/routes/forecast';
import { BreadcrumbItem } from '@/types';

import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from "@/components/ui/select";
import { Alert, AlertTitle, AlertDescription } from "@/components/ui/alert";
import { Separator } from "@/components/ui/separator";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Reabasteciemiento de productos',
        href: index().url,
    },
];

// --- 1. Interfaces TypeScript ---
interface Product {
    id: number;
    name: string;
}

interface ForecastResult {
    producto: Product;
    daysToForecast: number;
    currentStock: number;
    predictedDemand: number;
    unitsToBuy: number;
}

// --- 2. Props (Tipadas con TypeScript) ---
const props = defineProps<{
    products: Product[];
    initialResults?: ForecastResult | null;
}>();


// --- 3. Estado ---
const form = useForm({
    // Usamos 'name' si el modelo Product tiene 'name', si no, usa 'nombre'
    producto_id: props.products.length > 0 ? props.products[0].id : null,
    dias_a_predecir: 90,
});

const results = ref<ForecastResult | null>(props.initialResults || null);

// --- 4. Método de Cálculo (Inertia) ---
const calculateForecast = () => {
    // CORRECCIÓN 1: Usar la función global route() para generar la URL correcta
    form.post(calculate.url(), {
        preserveState: true,
        preserveScroll: true,

        onSuccess: (page) => {
            const newResults = page.props.initialResults as ForecastResult | undefined;
            if (newResults) {
                results.value = newResults;
            }
            form.errors = {};
        },
        onError: () => {
            // Los errores de validación de Laravel (422) se manejarán automáticamente 
            // por `useForm` y se mostrarán bajo los inputs correspondientes.
        }
    });
};
</script>

<template>

    <Head title="Predicción de Inventario" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="max-w-3xl mx-auto space-y-6">

                <!-- CARD PRINCIPAL -->
                <Card>
                    <CardHeader>
                        <CardTitle class="text-2xl font-bold">
                            📊 Herramienta de Predicción de Inventario
                        </CardTitle>
                        <p class="text-muted-foreground">
                            Calcula la demanda proyectada y la cantidad sugerida a comprar.
                        </p>
                    </CardHeader>

                    <CardContent>
                        <form @submit.prevent="calculateForecast" class="space-y-6">

                            <!-- PRODUCTO -->
                            <div class="space-y-2">
                                <Label>Producto a analizar</Label>

                                <Select v-model="form.producto_id">
                                    <SelectTrigger>
                                        <SelectValue placeholder="Seleccione un producto" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="product in products" :key="product.id" :value="product.id">
                                            {{ product.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>

                                <p v-if="form.errors.producto_id" class="text-red-500 text-sm">
                                    {{ form.errors.producto_id }}
                                </p>
                            </div>

                            <!-- DÍAS A PREDECIR -->
                            <div class="space-y-2">
                                <Label>Días a predecir</Label>
                                <Input type="number" v-model.number="form.dias_a_predecir" min="1" max="365"
                                    placeholder="Ej: 30" />
                                <small class="text-muted-foreground">
                                    Se usará el mismo período del año anterior como base.
                                </small>

                                <p v-if="form.errors.dias_a_predecir" class="text-red-500 text-sm">
                                    {{ form.errors.dias_a_predecir }}
                                </p>
                            </div>

                            <!-- BOTÓN -->
                            <Button type="submit" class="w-full" :disabled="form.processing">
                                <span v-if="!form.processing">📈 Obtener Predicción</span>
                                <span v-else>Calculando...</span>
                            </Button>
                        </form>
                    </CardContent>
                </Card>

                <Separator />

                <!-- RESULTADOS -->
                <div v-if="results">
                    <Card>
                        <CardHeader>
                            <CardTitle class="text-xl">
                                Resultados para: <strong>{{ results.producto.name }}</strong>
                            </CardTitle>
                        </CardHeader>

                        <CardContent class="space-y-6">

                            <!-- GRID -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <Card class="border-l-4 border-blue-500 p-4">
                                    <CardHeader>
                                        <CardTitle class="text-lg">Stock Actual</CardTitle>
                                    </CardHeader>
                                    <CardContent>
                                        <p class="text-3xl font-bold">
                                            {{ results.currentStock }} unidades
                                        </p>
                                    </CardContent>
                                </Card>

                                <Card class="border-l-4 border-orange-500 p-4">
                                    <CardHeader>
                                        <CardTitle class="text-lg">
                                            Demanda proyectada ({{ results.daysToForecast }} días)
                                        </CardTitle>
                                    </CardHeader>
                                    <CardContent>
                                        <p class="text-3xl font-bold">
                                            {{ results.predictedDemand }} unidades
                                        </p>
                                    </CardContent>
                                </Card>
                            </div>

                            <Separator />

                            <!-- UNIDADES A COMPRAR -->
                            <Alert class="border-green-500">
                                <AlertTitle class="text-lg font-semibold">
                                    🛒 Unidades sugeridas a comprar
                                </AlertTitle>
                                <AlertDescription>
                                    <span class="text-3xl font-extrabold text-green-600">
                                        {{ results.unitsToBuy }} unidades
                                    </span>
                                </AlertDescription>
                            </Alert>

                            <!-- MENSAJE FINAL -->
                            <Alert v-if="results.unitsToBuy > 0" variant="default">
                                <AlertDescription>
                                    Para cubrir la demanda proyectada debes comprar
                                    <strong>{{ results.unitsToBuy }}</strong> unidades.
                                </AlertDescription>
                            </Alert>

                            <Alert v-else class="border-l-4 border-green-600">
                                <AlertDescription>
                                    Tu stock actual cubre los próximos
                                    <strong>{{ results.daysToForecast }}</strong> días.
                                </AlertDescription>
                            </Alert>

                        </CardContent>
                    </Card>
                </div>

            </div>
        </div>
    </AppLayout>
</template>