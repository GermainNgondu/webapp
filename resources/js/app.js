import Dropzone from "dropzone";
import Quill from 'quill';
import 'quill/dist/quill.snow.css'; // Theme Snow (Pro)

window.Quill = Quill;

// On l'attache à window pour y accéder depuis Alpine.js
window.Dropzone = Dropzone;

// Optionnel : Désactiver l'auto-découverte pour garder le contrôle total
window.Dropzone.autoDiscover = false;