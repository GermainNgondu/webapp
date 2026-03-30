import Dropzone from "dropzone";
import Quill from 'quill';
import 'quill/dist/quill.snow.css';
import Sortable from 'sortablejs';
import flatpickr from "flatpickr";
import { French } from "flatpickr/dist/l10n/fr.js";
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';
import 'tippy.js/themes/light-border.css';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import Chart from 'chart.js/auto';



// Fix pour les icônes par défaut de Leaflet avec Vite
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: new URL('leaflet/dist/images/marker-icon-2x.png', import.meta.url).href,
    iconUrl: new URL('leaflet/dist/images/marker-icon.png', import.meta.url).href,
    shadowUrl: new URL('leaflet/dist/images/marker-shadow.png', import.meta.url).href,
});

window.flatpickr = flatpickr;

window.Sortable = Sortable;

window.Quill = Quill;

// On l'attache à window pour y accéder depuis Alpine.js
window.Dropzone = Dropzone;

// Optionnel : Désactiver l'auto-découverte pour garder le contrôle total
window.Dropzone.autoDiscover = false;

window.FullCalendar = {
    Calendar,
    plugins: {
        dayGridPlugin,
        timeGridPlugin,
        listPlugin,
        interactionPlugin
    }
};

window.tippy = tippy;

window.L = L;

window.Chart = Chart;

// On définit la langue par défaut
flatpickr.localize(French);