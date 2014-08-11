monitor_site
============
简述：
   系统级别的业务曲线监控， 负责业务系统曲线管理及曲线数据展示(数据来源于monitor_collector服务的采集)
   
功能描述：
   1. 用户管理  增删改查
   2. 增删改查曲线
   3. 曲线数据查看
   
实现：
   1. php框架 简单实现的mvc分离 基础服务类的封装(library)  session cache封装 等
   2. 业务数据存储： mongodb
   3. 曲线： highcharts

   
