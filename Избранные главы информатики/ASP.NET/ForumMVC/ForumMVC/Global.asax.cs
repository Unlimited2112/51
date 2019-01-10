using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Http;
using System.Web.Mvc;
using System.Web.Optimization;
using System.Web.Routing;
using System.Web.Security;
using System.Security.Principal;

namespace ForumMVC
{
    // Примечание: Инструкции по включению классического режима IIS6 или IIS7 
    // см. по ссылке http://go.microsoft.com/?LinkId=9394801

    public class MvcApplication : System.Web.HttpApplication
    {
        protected void Application_Start()
        {
            AreaRegistration.RegisterAllAreas();

            WebApiConfig.Register(GlobalConfiguration.Configuration);
            FilterConfig.RegisterGlobalFilters(GlobalFilters.Filters);
            RouteConfig.RegisterRoutes(RouteTable.Routes);
            BundleConfig.RegisterBundles(BundleTable.Bundles);
        }

        // По результатам авторизации сохраняем роль пользователя
        protected void Application_AuthenticateRequest(object sender, EventArgs e)
        {
            HttpApplication appl = (HttpApplication)sender;
            if (appl.Request.IsAuthenticated && appl.User.Identity is FormsIdentity)
            {
                HttpCookie authCookie = Request.Cookies[FormsAuthentication.FormsCookieName];
                if (authCookie != null)
                {
                    FormsIdentity identity = (FormsIdentity)appl.User.Identity;
                    FormsAuthenticationTicket authTicket = FormsAuthentication.Decrypt(authCookie.Value);
                    string roles = authTicket.UserData;
                    appl.Context.User = new GenericPrincipal(identity, new string[] { roles });
                }
            }
        }
    }
}