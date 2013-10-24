#include <linux/module.h>
#include <linux/vermagic.h>
#include <linux/compiler.h>

MODULE_INFO(vermagic, VERMAGIC_STRING);

struct module __this_module
__attribute__((section(".gnu.linkonce.this_module"))) = {
	.name = KBUILD_MODNAME,
	.init = init_module,
#ifdef CONFIG_MODULE_UNLOAD
	.exit = cleanup_module,
#endif
	.arch = MODULE_ARCH_INIT,
};

static const struct modversion_info ____versions[]
__used
__attribute__((section("__versions"))) = {
	{ 0x8a4e1261, "module_layout" },
	{ 0xadb5559d, "param_ops_byte" },
	{ 0xfde6d8ae, "no_llseek" },
	{ 0x43ca6cf0, "driver_unregister" },
	{ 0x509d512d, "class_destroy" },
	{ 0xa28fbde5, "spi_register_driver" },
	{ 0x6bc3fbc0, "__unregister_chrdev" },
	{ 0xfe7ba5f9, "__class_create" },
	{ 0x1d83da44, "__register_chrdev" },
	{ 0x403f9529, "gpio_request_one" },
	{ 0xc2165d85, "__arm_iounmap" },
	{ 0x40a6f522, "__arm_ioremap" },
	{ 0xff178f6, "__aeabi_idivmod" },
	{ 0xb9e52429, "__wake_up" },
	{ 0x1c132024, "request_any_context_irq" },
	{ 0xb51c7133, "nonseekable_open" },
	{ 0xfbc74f64, "__copy_from_user" },
	{ 0x27bbf221, "disable_irq_nosync" },
	{ 0xfcec0987, "enable_irq" },
	{ 0x6c8d5ae8, "__gpio_get_value" },
	{ 0x5bae3c90, "spi_async" },
	{ 0xf9a482f9, "msleep" },
	{ 0x6a4f4782, "spi_sync" },
	{ 0xfa2a45e, "__memzero" },
	{ 0x2196324, "__aeabi_idiv" },
	{ 0x7d11c268, "jiffies" },
	{ 0x8834396c, "mod_timer" },
	{ 0xbe2c0274, "add_timer" },
	{ 0xfb0e29f, "init_timer_key" },
	{ 0xc996d097, "del_timer" },
	{ 0x8f678b07, "__stack_chk_guard" },
	{ 0xf0fdf6cb, "__stack_chk_fail" },
	{ 0xa632f9a8, "spi_add_device" },
	{ 0x73e20c1c, "strlcpy" },
	{ 0xc1de4955, "bus_find_device_by_name" },
	{ 0xb81960ca, "snprintf" },
	{ 0x906ddedb, "spi_alloc_device" },
	{ 0x9fafff8c, "spi_busnum_to_master" },
	{ 0xc8b57c27, "autoremove_wake_function" },
	{ 0x8893fa5d, "finish_wait" },
	{ 0x75a17bed, "prepare_to_wait" },
	{ 0x1000e51, "schedule" },
	{ 0x67c2fa54, "__copy_to_user" },
	{ 0x99bb8806, "memmove" },
	{ 0x2e5810c6, "__aeabi_unwind_cpp_pr1" },
	{ 0xb3719537, "put_device" },
	{ 0xa539e9bc, "device_unregister" },
	{ 0xfe990052, "gpio_free" },
	{ 0x3ce4ca6f, "disable_irq" },
	{ 0xf20dabd8, "free_irq" },
	{ 0x625c7777, "kmalloc_caches" },
	{ 0x676bbc0f, "_set_bit" },
	{ 0xf0bc0a50, "device_create" },
	{ 0xd3dbfbc4, "_find_first_zero_bit_le" },
	{ 0x63b87fc5, "__init_waitqueue_head" },
	{ 0xe1816829, "kmem_cache_alloc" },
	{ 0x62b72b0d, "mutex_unlock" },
	{ 0x49ebacbd, "_clear_bit" },
	{ 0x7f6c7e7c, "device_destroy" },
	{ 0xe16b893b, "mutex_lock" },
	{ 0xbe4f8561, "dev_set_drvdata" },
	{ 0x27e1a049, "printk" },
	{ 0x6fbc8ee3, "dev_get_drvdata" },
	{ 0x37a0cba, "kfree" },
	{ 0x43b0c9c3, "preempt_schedule" },
	{ 0xefd6cf06, "__aeabi_unwind_cpp_pr0" },
};

static const char __module_depends[]
__used
__attribute__((section(".modinfo"))) =
"depends=";


MODULE_INFO(srcversion, "5F9C5791FD704C89F969CC2");
