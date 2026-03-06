<template>
  <div class="footer-container">
    <div class="language-footer mt-4 text-sm text-gray-500 dark:text-gray-400">
      <div class="relative inline-block text-left mr-4">
        <!-- Language Dropdown Button -->
        <button
            @click.stop="toggleLanguageDropdown"
            type="button"
            class="inline-flex items-center justify-center gap-x-1.5 px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-700"
            :aria-expanded="isLanguageOpen"
            aria-haspopup="true"
        >
          <GlobeAltIcon class="h-5 w-5"/>
          {{ $t('common.language') }}
          <ChevronDownIcon class="-mr-1 h-5 w-5"/>
        </button>

        <!-- Language Dropdown Menu -->
        <div
            v-show="isLanguageOpen"
            class="absolute right-0 bottom-full mb-2 z-10 mt-2 w-32 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            role="menu"
            aria-orientation="vertical"
            aria-labelledby="menu-button"
            tabindex="-1"
        >
          <div class="py-1" role="none">
            <a
                v-for="locale in availableLocales"
                :key="locale.code"
                href="#"
                @click.prevent="changeLocale(locale.code)"
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center"
                role="menuitem"
                tabindex="-1"
            >
              <CheckIcon v-if="currentLocale === locale.code" class="h-4 w-4 mr-2 text-green-600 dark:text-green-500"/>
              <span :class="{'pl-6': currentLocale !== locale.code}">{{ locale.name }}</span>
            </a>
          </div>
        </div>
      </div>

      <!-- Theme Dropdown -->
      <div class="relative inline-block text-left">
        <!-- Theme Dropdown Button -->
        <button
            @click.stop="toggleThemeDropdown"
            type="button"
            class="inline-flex items-center justify-center gap-x-1.5 px-3 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-700"
            :aria-expanded="isThemeOpen"
            aria-haspopup="true"
        >
          <SunIcon v-if="currentTheme === 'light'" class="h-5 w-5"/>
          <MoonIcon v-if="currentTheme === 'dark'" class="h-5 w-5"/>
          <SunIcon v-if="currentTheme === 'system'" class="h-5 w-5"/>
          {{ $t('common.theme') }}
          <ChevronDownIcon class="-mr-1 h-5 w-5"/>
        </button>

        <!-- Theme Dropdown Menu -->
        <div
            v-show="isThemeOpen"
            class="absolute right-0 bottom-full mb-2 z-10 mt-2 w-36 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            role="menu"
            aria-orientation="vertical"
            aria-labelledby="menu-button"
            tabindex="-1"
        >
          <div class="py-1" role="none">
            <a
                href="#"
                @click.prevent="changeTheme('system')"
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center"
                role="menuitem"
                tabindex="-1"
            >
              <CheckIcon v-if="currentTheme === 'system'" class="h-4 w-4 mr-2 text-green-600 dark:text-green-500"/>
              <span :class="{'pl-6': currentTheme !== 'system'}" class="flex items-center">
                <DesktopComputerIcon class="h-4 w-4 mr-2"/>
                {{ $t('common.system') }}
              </span>
            </a>
            <a
                href="#"
                @click.prevent="changeTheme('light')"
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center"
                role="menuitem"
                tabindex="-1"
            >
              <CheckIcon v-if="currentTheme === 'light'" class="h-4 w-4 mr-2 text-green-600 dark:text-green-500"/>
              <span :class="{'pl-6': currentTheme !== 'light'}" class="flex items-center">
                <SunIcon class="h-4 w-4 mr-2"/>
                {{ $t('common.light') }}
              </span>
            </a>
            <a
                href="#"
                @click.prevent="changeTheme('dark')"
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center"
                role="menuitem"
                tabindex="-1"
            >
              <CheckIcon v-if="currentTheme === 'dark'" class="h-4 w-4 mr-2 text-green-600 dark:text-green-500"/>
              <span :class="{'pl-6': currentTheme !== 'dark'}" class="flex items-center">
                <MoonIcon class="h-4 w-4 mr-2"/>
                {{ $t('common.dark') }}
              </span>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Copyright section -->
    <div class="copyright-section mt-4 text-center text-sm text-gray-500 dark:text-gray-400">
      <p>© {{ currentYear }} {{ $page.props.central.name }}. {{ $t('common.copyright') }}</p>
    </div>
  </div>
</template>

<script setup>
import {computed, onMounted, onUnmounted, ref} from 'vue';
import {usePage} from '@inertiajs/inertia-vue3';
import {useI18n} from 'vue-i18n';
import {loadTranslations, setLocale} from '@/i18n';
import axios from 'axios';
import {CheckIcon, ChevronDownIcon, DesktopComputerIcon, GlobeAltIcon, MoonIcon, SunIcon} from '@heroicons/vue/outline';

// 控制语言下拉菜单的显示
const isLanguageOpen = ref(false);

function toggleLanguageDropdown(event) {
  event.stopPropagation();
  isLanguageOpen.value = !isLanguageOpen.value;
  if (isLanguageOpen.value) {
    isThemeOpen.value = false;
  }
}

// 控制主题下拉菜单的显示
const isThemeOpen = ref(false);

function toggleThemeDropdown(event) {
  event.stopPropagation();
  isThemeOpen.value = !isThemeOpen.value;
  if (isThemeOpen.value) {
    isLanguageOpen.value = false;
  }
}

// 点击其他地方关闭下拉菜单
function closeDropdowns(event) {
  isLanguageOpen.value = false;
  isThemeOpen.value = false;
}

onMounted(() => {
  document.addEventListener('click', closeDropdowns);
});

onUnmounted(() => {
  document.removeEventListener('click', closeDropdowns);
});

// 使用i18n
const {t, locale} = useI18n();

// 从页面属性中获取当前语言和可用语言
const page = usePage();
const currentLocale = ref(page.props.value.locale || 'en');

// 获取可用语言列表
const availableLocales = computed(() => {
  const locales = page.props.value.available_locales || {};
  return Object.keys(locales).map(code => ({
    code,
    name: locales[code]
  }));
});

// 获取当前年份用于版权信息
const currentYear = computed(() => new Date().getFullYear());

// 切换语言
async function changeLocale(localeCode) {
  try {
    // 如果已经是当前语言，则不进行切换
    if (currentLocale.value === localeCode) {
      return;
    }

    // 更新服务器端语言设置
    await axios.post('/locale', {locale: localeCode});
    // 更新客户端语言设置
    await setLocale(localeCode);
    // 重新加载翻译
    await loadTranslations();
    // 更新当前组件的语言状态
    currentLocale.value = localeCode;
    // 存储用户语言偏好到localStorage，以便在会话之间保持语言选择
    localStorage.setItem('preferred_locale', localeCode);
    // 关闭下拉菜单
    isLanguageOpen.value = false;
  } catch (error) {
    console.error('Failed to change locale:', error);
  }
}

// 主题相关
const currentTheme = ref(localStorage.getItem('theme') || 'system');
const prefersDark = ref(false);

// 检测系统偏好
function checkSystemPreference() {
  prefersDark.value = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  applyTheme();
}

// 监听系统偏好的变化
function setupSystemPreferenceListener() {
  const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
  if (mediaQuery.addEventListener) {
    mediaQuery.addEventListener('change', (e) => {
      prefersDark.value = e.matches;
      if (currentTheme.value === 'system') {
        applyTheme();
      }
    });
  }
}

// 应用主题
function applyTheme() {
  const htmlElement = document.documentElement;
  const isDark = currentTheme.value === 'dark' ||
      (currentTheme.value === 'system' && prefersDark.value);

  if (isDark) {
    htmlElement.classList.add('dark');
  } else {
    htmlElement.classList.remove('dark');
  }
}

// 切换主题
function changeTheme(theme) {
  // 设置当前主题
  currentTheme.value = theme;

  // 保存到 localStorage
  localStorage.setItem('theme', theme);

  // 应用主题
  applyTheme();

  // 关闭下拉菜单
  isThemeOpen.value = false;
}

// 组件挂载时确保语言和主题设置正确
onMounted(() => {
  // 检查localStorage中是否有存储的语言偏好
  const preferredLocale = localStorage.getItem('preferred_locale');

  // 如果有存储的语言偏好，并且与当前语言不同，则应用该语言偏好
  if (preferredLocale && preferredLocale !== currentLocale.value) {
    changeLocale(preferredLocale);
  }
  // 否则确保组件的locale与i18n的locale同步
  else if (currentLocale.value !== locale.value) {
    setLocale(currentLocale.value);
  }

  // 检测系统颜色偏好
  checkSystemPreference();

  // 设置系统偏好变化监听
  setupSystemPreferenceListener();
})
</script>

<style scoped>
.footer-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
  padding-bottom: 1rem;
}
</style>