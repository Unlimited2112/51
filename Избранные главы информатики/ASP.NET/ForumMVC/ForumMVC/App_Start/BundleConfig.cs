using System.Web;
using System.Web.Optimization;

namespace ForumMVC
{
    public class BundleConfig
    {
        // Дополнительные сведения о Bundling см. по адресу http://go.microsoft.com/fwlink/?LinkId=254725
        public static void RegisterBundles(BundleCollection bundles)
        {
            bundles.Add(new ScriptBundle("~/bundles/jquery").Include(
                        "~/Scripts/jquery-{version}.js"));

            bundles.Add(new ScriptBundle("~/bundles/jqueryval").Include(
                        "~/Scripts/jquery.unobtrusive*",
                        "~/Scripts/jquery.validate*"));

            bundles.Add(new StyleBundle("~/Content/css").Include("~/Content/Style.css"));

            bundles.Add(new StyleBundle("~/Content/bootstrap").Include(
                "~/Content/css/bootstrap.css",
                "~/Content/css/bootstrap-theme.css",
                "~/Content/css/ie10-viewport-bug-workaround.css"));

            bundles.Add(new ScriptBundle("~/bundles/bootstrap").Include(
                "~/Content/js/jquery.js",
                "~/Content/js/bootstrap.js",
                "~/Content/js/ie10-viewport-bug-workaround.js"));

            bundles.Add(new ScriptBundle("~/bundles/ie9").Include(
                "~/Content/js/html5shiv.js",
                "~/Content/js/respond.js"));

        }
    }
}