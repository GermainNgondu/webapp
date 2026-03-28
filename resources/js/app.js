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

// On définit la langue par défaut
flatpickr.localize(French);