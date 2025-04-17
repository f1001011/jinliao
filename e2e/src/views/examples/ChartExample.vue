<script setup lang="ts">
import { ref } from 'vue'
import PageContainer from '@/components/layout/PageContainer.vue'

// 条形图配置
const barChartOptions = ref({
  chart: {
    type: 'bar',
    toolbar: {
      show: false,
    },
  },
  colors: ['#FF6B00'],
  plotOptions: {
    bar: {
      borderRadius: 6,
      columnWidth: '50%',
    },
  },
  dataLabels: {
    enabled: false,
  },
  xaxis: {
    categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
  },
  yaxis: {
    title: {
      text: 'Orders',
    },
  },
  tooltip: {
    y: {
      formatter: function (val: number) {
        return val + ' orders'
      },
    },
  },
})

const barChartSeries = ref([
  {
    name: 'Daily Orders',
    data: [35, 41, 36, 26, 45, 48, 52],
  },
])

// 饼图配置
const pieChartOptions = ref({
  chart: {
    type: 'pie',
  },
  colors: ['#FF6B00', '#2C3E50', '#10B981', '#F59E0B', '#3B82F6'],
  labels: ['Burgers', 'Pizza', 'Sushi', 'Salads', 'Drinks'],
  responsive: [
    {
      breakpoint: 480,
      options: {
        chart: {
          width: 200,
        },
        legend: {
          position: 'bottom',
        },
      },
    },
  ],
})

const pieChartSeries = ref([35, 25, 20, 15, 5])

// 折线图配置
const lineChartOptions = ref({
  chart: {
    type: 'line',
    toolbar: {
      show: false,
    },
  },
  colors: ['#3B82F6'],
  stroke: {
    curve: 'smooth',
    width: 3,
  },
  xaxis: {
    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
  },
  markers: {
    size: 4,
    colors: ['#3B82F6'],
    strokeColors: '#fff',
    strokeWidth: 2,
    hover: {
      size: 6,
    },
  },
  tooltip: {
    y: {
      formatter: function (val: number) {
        return '$' + val.toFixed(2) + 'k'
      },
    },
  },
})

const lineChartSeries = ref([
  {
    name: 'Monthly Revenue',
    data: [31, 40, 28, 51, 42, 56, 65],
  },
])

// 面积图配置
const areaChartOptions = ref({
  chart: {
    type: 'area',
    toolbar: {
      show: false,
    },
  },
  colors: ['#10B981', '#F59E0B'],
  dataLabels: {
    enabled: false,
  },
  stroke: {
    curve: 'smooth',
    width: 2,
  },
  fill: {
    type: 'gradient',
    gradient: {
      shadeIntensity: 1,
      opacityFrom: 0.7,
      opacityTo: 0.2,
    },
  },
  xaxis: {
    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
  },
  tooltip: {
    y: {
      formatter: function (val: number) {
        return val + ' users'
      },
    },
  },
})

const areaChartSeries = ref([
  {
    name: 'New Users',
    data: [31, 40, 28, 51, 42, 56, 65],
  },
  {
    name: 'Returning Users',
    data: [20, 32, 25, 40, 32, 45, 52],
  },
])
</script>
