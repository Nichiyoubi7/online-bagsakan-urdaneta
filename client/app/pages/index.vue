<template>
  <GuestLayout>
    <HeroSection />
    <ProductsSection />
    <CategoriesSection />
    <DealsSection />
    <FeaturedShopsSection />
    <FarmBannerSection />
    <HowItWorksSection />
    <OurSellersSection />
  </GuestLayout>
</template>

<script setup lang="ts">
import GuestLayout from '~/components/layout/GuestLayout.vue'
import HeroSection from '~/components/HeroSection.vue'
import ProductsSection from '~/components/ProductsSection.vue'
import CategoriesSection from '~/components/CategoriesSection.vue'
import DealsSection from '~/components/DealsSection.vue'
import FeaturedShopsSection from '~/components/FeaturedShopsSection.vue'
import FarmBannerSection from '~/components/FarmBannerSection.vue'
import HowItWorksSection from '~/components/HowItWorksSection.vue'
import OurSellersSection from '~/components/OurSellersSection.vue'
</script>