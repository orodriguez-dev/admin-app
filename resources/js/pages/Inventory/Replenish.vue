<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const start_date = ref('');
const end_date = ref('');

const submit = () => {
    router.get('/inventory/replenish', {
        start_date: start_date.value,
        end_date: end_date.value
    });
};

const props = defineProps({
    products: Object,
    period: Object
});
</script>

<template>
    <div class="p-6">
        <h1 class="text-xl font-bold mb-4">Análisis de Reabastecimiento</h1>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label>Fecha Inicio</label>
                <input v-model="start_date" type="date" class="border p-2 rounded w-full">
            </div>

            <div>
                <label>Fecha Fin</label>
                <input v-model="end_date" type="date" class="border p-2 rounded w-full">
            </div>
        </div>

        <button @click="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            Consultar
        </button>

        <div v-if="products" class="mt-8">
            <h2 class="text-lg font-semibold mb-2">Resultados</h2>

            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">Producto</th>
                        <th class="border p-2">Ventas (Actual)</th>
                        <th class="border p-2">Ventas (Año Anterior)</th>
                        <th class="border p-2">Diferencia</th>
                        <th class="border p-2">¿Comprar?</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="p in props.products" :key="p.id">
                        <td class="border p-2">{{ p.name }}</td>
                        <td class="border p-2">{{ p.current_sales }}</td>
                        <td class="border p-2">{{ p.previous_sales }}</td>
                        <td class="border p-2">{{ p.difference }}</td>
                        <td class="border p-2">
                            <span
                                :class="p.needs_purchase ? 'text-red-600 font-bold' : 'text-green-600'"
                            >
                                {{ p.needs_purchase ? 'Sí' : 'No' }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</template>