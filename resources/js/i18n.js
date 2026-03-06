import { createI18n } from 'vue-i18n';
import axios from 'axios';

// 标记翻译是否已加载完成
let translationsLoaded = false;

// 创建i18n实例
const i18n = createI18n({
  legacy: false, // 使用Composition API模式
  globalInjection: true, // 全局注入$t方法
  locale: document.documentElement.lang || 'en', // 默认语言
  fallbackLocale: 'en', // 回退语言
  messages: {}, // 初始为空，将从API加载
  // 未加载翻译前，隐藏 key
  missing: (locale, key) => {
    return translationsLoaded ? key : '';
  },
});

// 缓存已加载的翻译数据
const loadedTranslations = new Map();
let lastLoadedLocale = null;

// 缓存过期时间（1小时）
const CACHE_EXPIRATION = 60 * 60 * 1000; // 1 hour in milliseconds

// 从localStorage获取缓存的翻译
function getCachedTranslations(locale) {
  const cacheKey = `translations_${locale}`;
  const cached = localStorage.getItem(cacheKey);
  
  if (cached) {
    try {
      const { data, timestamp } = JSON.parse(cached);
      // 检查缓存是否过期
      if (Date.now() - timestamp < CACHE_EXPIRATION) {
        return data;
      } else {
        // 如果缓存过期，删除它
        localStorage.removeItem(cacheKey);
      }
    } catch (error) {
      console.error('Failed to parse cached translations:', error);
      localStorage.removeItem(cacheKey);
    }
  }
  
  return null;
}

// 将翻译存储到localStorage
function cacheTranslations(locale, data) {
  const cacheKey = `translations_${locale}`;
  const cacheData = {
    data,
    timestamp: Date.now()
  };
  
  try {
    localStorage.setItem(cacheKey, JSON.stringify(cacheData));
  } catch (error) {
    console.error('Failed to cache translations:', error);
  }
}

// 从API加载翻译数据
export async function loadTranslations() {
  const currentLocale = i18n.global.locale.value;
  
  // 1. 检查内存缓存
  if (loadedTranslations.has(currentLocale) && lastLoadedLocale === currentLocale) {
    // 使用内存缓存的翻译数据
    const cachedTranslations = loadedTranslations.get(currentLocale);
    Object.keys(cachedTranslations).forEach(namespace => {
      i18n.global.mergeLocaleMessage(currentLocale, {
        [namespace]: cachedTranslations[namespace]
      });
    });
    translationsLoaded = true;
    return;
  }
  
  // 2. 检查localStorage缓存
  const cachedData = getCachedTranslations(currentLocale);
  if (cachedData) {
    // 使用localStorage缓存的翻译数据
    loadedTranslations.set(currentLocale, cachedData);
    lastLoadedLocale = currentLocale;
    
    Object.keys(cachedData).forEach(namespace => {
      i18n.global.mergeLocaleMessage(currentLocale, {
        [namespace]: cachedData[namespace]
      });
    });
    translationsLoaded = true;
    return;
  }
  
  // 3. 如果没有可用的缓存，从API加载
  try {
    const response = await axios.get('/translations');
    if (response.data) {
      // 缓存翻译数据 - 内存
      loadedTranslations.set(currentLocale, response.data);
      lastLoadedLocale = currentLocale;
      
      // 缓存翻译数据 - localStorage (带过期时间)
      cacheTranslations(currentLocale, response.data);
      
      // 设置翻译数据
      Object.keys(response.data).forEach(namespace => {
        i18n.global.mergeLocaleMessage(currentLocale, {
          [namespace]: response.data[namespace]
        });
      });
      translationsLoaded = true;
    }
  } catch (error) {
    console.error('Failed to load translations:', error);
  }
}

// 切换语言
export async function setLocale(locale) {
  try {
    // 如果语言没有变化，则不进行切换
    if (i18n.global.locale.value === locale) {
      return;
    }
    
    // 标记翻译未加载
    translationsLoaded = false;
    
    // 更新HTML lang属性
    document.documentElement.lang = locale;
    // 更新i18n语言
    i18n.global.locale.value = locale;
  } catch (error) {
    console.error(`Failed to set locale to ${locale}:`, error);
  }
}

export default i18n;