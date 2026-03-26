import Dropzone from "dropzone";
import Quill from 'quill';
import 'quill/dist/quill.snow.css';
import Sortable from 'sortablejs';
import flatpickr from "flatpickr";
import { French } from "flatpickr/dist/l10n/fr.js";

window.flatpickr = flatpickr;

window.Sortable = Sortable;

window.Quill = Quill;

// On l'attache à window pour y accéder depuis Alpine.js
window.Dropzone = Dropzone;

// Optionnel : Désactiver l'auto-découverte pour garder le contrôle total
window.Dropzone.autoDiscover = false;

// On définit la langue par défaut
flatpickr.localize(French);