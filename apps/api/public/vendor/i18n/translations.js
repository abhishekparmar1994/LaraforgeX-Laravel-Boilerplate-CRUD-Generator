/**
 * translations.js — LaraforgeX Universal Client-Side i18n Translation Dictionary
 */
(function (global) {
  'use strict';

  var translations = {
    en: {
      dashboard: 'Dashboard',
      crud_generator: 'Visual CRUD Generator',
      users: 'User Management',
      roles: 'Roles & Hierarchy',
      permissions: 'Permissions Matrix',
      media: 'Media Manager',
      settings: 'Platform Settings',
      profile: 'My Profile',
      logout: 'Log Out',
      search: 'Search platform...',
      notifications: 'Notifications',
      platform_config: 'Platform Configurations',
      two_factor: 'Two-Factor Auth',
      save_changes: 'Save Changes',
      welcome_back: 'Welcome Back',
      actions: 'Actions',
      status: 'Status',
      active: 'Active',
      disabled: 'Disabled',
      cancel: 'Cancel',
      delete: 'Delete',
      edit: 'Edit',
      view: 'View',
      create_new: 'Create New',
    },
    es: {
      dashboard: 'Panel de Control',
      crud_generator: 'Generador CRUD Visual',
      users: 'Gestión de Usuarios',
      roles: 'Roles y Jerarquía',
      permissions: 'Matriz de Permisos',
      media: 'Gestor de Medios',
      settings: 'Configuración del Sistema',
      profile: 'Mi Perfil',
      logout: 'Cerrar Sesión',
      search: 'Buscar...',
      notifications: 'Notificaciones',
      platform_config: 'Configuraciones de Plataforma',
      two_factor: 'Autenticación de 2 Factores',
      save_changes: 'Guardar Cambios',
      welcome_back: 'Bienvenido de nuevo',
      actions: 'Acciones',
      status: 'Estado',
      active: 'Activo',
      disabled: 'Deshabilitado',
      cancel: 'Cancelar',
      delete: 'Eliminar',
      edit: 'Editar',
      view: 'Ver',
      create_new: 'Crear Nuevo',
    },
    fr: {
      dashboard: 'Tableau de Bord',
      crud_generator: 'Générateur CRUD Visuel',
      users: 'Gestion des Utilisateurs',
      roles: 'Rôles et Hiérarchie',
      permissions: 'Matrice des Permissions',
      media: 'Gestionnaire de Médias',
      settings: 'Paramètres du Système',
      profile: 'Mon Profil',
      logout: 'Déconnexion',
      search: 'Rechercher...',
      notifications: 'Notifications',
      platform_config: 'Configurations de la Plateforme',
      two_factor: 'Auth à Deux Facteurs',
      save_changes: 'Enregistrer les modifications',
      welcome_back: 'Bon retour',
      actions: 'Actions',
      status: 'Statut',
      active: 'Actif',
      disabled: 'Désactivé',
      cancel: 'Annuler',
      delete: 'Supprimer',
      edit: 'Modifier',
      view: 'Voir',
      create_new: 'Créer Nouveau',
    },
    de: {
      dashboard: 'Übersicht',
      crud_generator: 'Visueller CRUD-Generator',
      users: 'Benutzerverwaltung',
      roles: 'Rollen & Hierarchie',
      permissions: 'Berechtigungsmatrix',
      media: 'Medien-Manager',
      settings: 'System-Einstellungen',
      profile: 'Mein Profil',
      logout: 'Abmelden',
      search: 'Suchen...',
      notifications: 'Benachrichtigungen',
      platform_config: 'Plattform-Konfigurationen',
      two_factor: 'Zwei-Faktor-Auth',
      save_changes: 'Änderungen speichern',
      welcome_back: 'Willkommen zurück',
      actions: 'Aktionen',
      status: 'Status',
      active: 'Aktiv',
      disabled: 'Deaktiviert',
      cancel: 'Abbrechen',
      delete: 'Löschen',
      edit: 'Bearbeiten',
      view: 'Anzeigen',
      create_new: 'Neu erstellen',
    },
    ar: {
      dashboard: 'لوحة التحكم',
      crud_generator: 'مولد CRUD المرئي',
      users: 'إدارة المستخدمين',
      roles: 'الأدوار والتسلسل الهرمي',
      permissions: 'مصفوفة الأذونات',
      media: 'مدير الوسائط',
      settings: 'إعدادات النظام',
      profile: 'ملفي الشخصي',
      logout: 'تسجيل الخروج',
      search: 'بحث في النظام...',
      notifications: 'الإشعارات',
      platform_config: 'إعدادات المنصة',
      two_factor: 'المصادقة الثنائية',
      save_changes: 'حفظ التغييرات',
      welcome_back: 'مرحباً بعودتك',
      actions: 'الإجراءات',
      status: 'الحالة',
      active: 'نشط',
      disabled: 'معطل',
      cancel: 'إلغاء',
      delete: 'حذف',
      edit: 'تعديل',
      view: 'عرض',
      create_new: 'إنشاء جديد',
    }
  };

  global.i18nTranslations = translations;

  global.translatePage = function (langCode) {
    var dict = translations[langCode] || translations.en;
    document.querySelectorAll('[data-i18n]').forEach(function (el) {
      var key = el.getAttribute('data-i18n');
      if (dict[key]) {
        if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
          if (el.hasAttribute('placeholder')) {
            el.setAttribute('placeholder', dict[key]);
          } else {
            el.value = dict[key];
          }
        } else {
          el.textContent = dict[key];
        }
      }
    });
  };

  /* Auto-restore translation and RTL layout on DOM load */
  if (typeof document !== 'undefined') {
    var initTranslation = function () {
      var savedCode = localStorage.getItem('laraforgex_lang_code') || 'en';
      var savedFlag = localStorage.getItem('laraforgex_lang_flag') || '🇺🇸';
      var savedLabel = localStorage.getItem('laraforgex_lang_label') || 'EN';
      var savedDir = localStorage.getItem('laraforgex_lang_dir') || 'ltr';

      document.documentElement.setAttribute('dir', savedDir);

      var flagEl = document.getElementById('current-lang-flag');
      var codeEl = document.getElementById('current-lang-code');
      if (flagEl) flagEl.textContent = savedFlag;
      if (codeEl) codeEl.textContent = savedLabel;

      global.translatePage(savedCode);
    };

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initTranslation);
    } else {
      initTranslation();
    }
  }

})(typeof window !== 'undefined' ? window : this);
