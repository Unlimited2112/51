using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using System.Web.Security;
using ForumMVC.Models;
using ForumMVC.Helpers;
using PagedList.Mvc;
using PagedList;
using System.Data.Entity;

namespace ForumMVC.Controllers
{
    // Контроллер авторизации и управления данными пользователей
    public class AccountController : Controller
    {
        // Контекст подключения к БД с помощью Entity Framework
        ForumContext db = new ForumContext();
        // Вывод формы авторизации
        public ActionResult Login()
        {
            return View();
        }
        // Обработка формы авторизации
        [HttpPost]
        public ActionResult Login(LogOnModel model, string returnUrl)
        {
            // Если модель прошла валидацию
            if (ModelState.IsValid)
            {
                // Если авторизация удачна
                if (LoginHelper.LoginUser(Response, model.UserName, model.Password))
                {
                    // редирект на предыдущую, если url локальный
                    if (Url.IsLocalUrl(returnUrl))
                    {
                        return Redirect(returnUrl);
                    }
                    // редирект на главную
                    else
                    {
                        return RedirectToAction("Index", "Default");
                    }
                }
                // иначе добавляем сведения об ошибке
                else
                {
                    ModelState.AddModelError("", "Неверный логин или пароль!");
                }
            }
            return View(model);
        }
        // Обработка выхода авторизованного пользователя
        public ActionResult LogOff()
        {
            FormsAuthentication.SignOut();
            return RedirectToAction("Index", "Default");
        }
        // Вывод формы регистрации
        public ActionResult Register()
        {
            return View();
        }
        // Обработка формы регистрации
        [HttpPost]
        public ActionResult Register(RegisterModel model)
        {
            if (ModelState.IsValid)
            {
                // Если регистрация удачна
                if (LoginHelper.RegisterUser(Response, model.UserName, model.Password))
                {
                    // редирект на главную
                    return RedirectToAction("Index", "Default");
                }
                // иначе добавляем сведения об ошибке
                else
                {
                    ModelState.AddModelError("", "Ошибка при регистрации");
                }
            }
            return View(model);
        }
        // Функция для возвращения результата валидации по наличию логина в БД
        public JsonResult CheckUserName(string username)
        {
            var result = LoginHelper.ValidateLogin(username);
            return Json(result, JsonRequestBehavior.AllowGet);
        }
        // Вывод списка пользователей
        [Authorize(Roles = "Admin")]
        public ActionResult List(int? id)
        {
            // Получаем список пользователей
            List<User> users = db.Users.Include("Role").ToList();
            // Задаем данные для постраничного вывода
            int pageSize = 10;
            int pageNumber = (id ?? 1);
            return View(users.ToPagedList(pageNumber, pageSize));
        }

        // Страница профиля пользователя
        public ActionResult Profile(string id)
        {
            // Получаем редактируемого пользователя из БД
            User user = db.Users.Include("Messages").Include("Topics").Include("Role").FirstOrDefault(p => p.Login == id);
            // Если пользователm есть в БД продолжаем
            if (user != null)
            {
                ViewBag.UserId = user.Id;
                ViewBag.RoleId = user.RoleId;
                ViewBag.RoleName = user.Role.Description;
                ViewBag.UserName = user.Login;
                ViewBag.CntTopics = user.Topics.Count;
                ViewBag.CntMsgs = user.Messages.Count;
                return View();
            }
            // иначе выводим ошибку
            else
            {
                return View("Error");
            }
        }
        // Вывод формы изменения пароля
        // доступна только авторизованным пользователям
        [Authorize]
        public ActionResult ChangePassword(int? id)
        {
            // Получаем редактируемого пользователя из БД
            User user = db.Users.FirstOrDefault(p => p.Id == id);
            // Если пользователь найден и совпадает с авторизованным пользователем
            if (user != null && user.Login == User.Identity.Name)
            {
                ChangePasswordModel model = new ChangePasswordModel();
                model.UserId = user.Id;
                return View(model);
            }
            // иначе выводим ошибку
            else
            {
                return View("Error");
            }
        }
        // Обработка формы изменения пароля
        // доступна только авторизованным пользователям
        [Authorize]
        [HttpPost]
        public ActionResult ChangePassword(ChangePasswordModel model)
        {
            if (ModelState.IsValid)
            {
                User user = db.Users.FirstOrDefault(p => p.Id == model.UserId);
                if (user != null)
                {
                    // Если введенный старый пароль совпадает с паролем в БД
                    if (user.Password == LoginHelper.GetHash(model.PasswordOld))
                    {
                        // Изменяем пароль
                        user.Password = LoginHelper.GetHash(model.Password);
                        db.Entry(user).State = EntityState.Modified;
                        db.SaveChanges();
                        return RedirectToAction("Profile/" + user.Login, "Account");
                    }
                    // иначе добавляем сообщение об ошибке
                    else
                    {
                        ModelState.AddModelError("", "Неверный старый пароль!");
                    }
                }
                else
                {
                    ModelState.AddModelError("", "Ошибка при изменении пароля...");
                }
            }
            return View(model);
        }
        // Вывод формы изменения роли
        // доступна только админу и модератору
        [Authorize(Roles = "Admin, Moder")]
        public ActionResult ChangeRole(int? id)
        {
            // Получаем редактируемого пользователя из БД
            User user = db.Users.FirstOrDefault(p => p.Id == id);
            // Если пользователь найден
            if (user != null)
            {
                // Формируем список для dropdown
                SelectList roles;
                // Админ может присвоить роль модератора, пользователя или забанить
                if (User.IsInRole("Admin"))
                {
                    roles = new SelectList(db.Roles.Where(p => p.Id > 1), "Id", "Description");
                }
                // Модератор может присвоить роль пользователя или забанить
                else
                {
                    roles = new SelectList(db.Roles.Where(p => p.Id > 2), "Id", "Description");
                }
                ViewBag.Roles = roles;
                ViewBag.UserName = user.Login;
                ChangeRoleModel model = new ChangeRoleModel();
                model.UserId = user.Id;
                model.RoleId = (int)user.RoleId;
                return View(model);
            }
            // иначе выводим ошибку
            else
            {
                return View("Error");
            }
        }
        // Обработка формы изменения роли
        [Authorize(Roles = "Admin, Moder")]
        [HttpPost]
        public ActionResult ChangeRole(ChangeRoleModel model)
        {
            if (ModelState.IsValid)
            {
                // Получаем пользователя и роль из БД
                User user = db.Users.FirstOrDefault(p => p.Id == model.UserId);
                Role role = db.Roles.FirstOrDefault(p => p.Id == model.RoleId);
                // Если такие записи найдены в БД
                if (user != null && role != null)
                {
                    // Изменяем и сохраняем запись пользователя
                    user.Role = role;
                    db.Entry(user).State = EntityState.Modified;
                    db.SaveChanges();
                    return RedirectToAction("Profile/" + user.Login, "Account");
                }
                // иначе добавляем сообщение об ошибке
                else
                {
                    ModelState.AddModelError("", "Ошибка при изменении роли...");
                }
            }
            else
            {
                ModelState.AddModelError("", "Ошибка при изменении роли...");
            }
            return View(model);
        }

    }
}
