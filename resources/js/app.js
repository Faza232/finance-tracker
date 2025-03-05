import './bootstrap';
import "../css/app.css";
import Chart from 'chart.js/auto';
import { ARIMA } from 'arima';
window.Chart = Chart; // Buat Chart tersedia secara global
window.ARIMA = ARIMA; // Buat ARIMA tersedia secara global