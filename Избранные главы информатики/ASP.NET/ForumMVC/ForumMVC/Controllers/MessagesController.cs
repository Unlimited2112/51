using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using ForumMVC.Models;
using PagedList.Mvc;
using PagedList;
using System.Data.Entity;

namespace ForumMVC.Controllers
{
    // Контроллер работы с сообщениями
    public class MessagesController : Controller
    {
        ForumContext db = new ForumContext();
        // Вывод страницы с ошибкой
        public ActionResult Index()
        {
            return View("Error");
        }
        // Вывод списка сообщений темы
        public ActionResult List(int? id, int? page)
        {
            // Получаем запись темы из БД
            Topic topic = db.Topics.Include("Rubric").Include("User").FirstOrDefault(p => p.Id == id);
            // Если такая запись есть
            if (topic != null)
            {
                // формируем набор данных для списка
                ViewBag.Topic = topic;
                List<Message> messages = db.Messages.Include("User").Where(p => p.TopicId == id).OrderBy(p => p.Dates).ToList();
                ViewBag.MsgCnt = messages.Count;
                // параметры постраничного вывода
                int pageSize = 10;
                int pageNumber = (page ?? 1);
                // выводим представление
                return View(messages.ToPagedList(pageNumber, pageSize));
            }
            // иначе выводим страницу с ошибкой
            else
            {
                return View("Error");
            }
        }
        // Вывод формы добавления сообщения
        // доступна администратору, модератору и пользователю
        [Authorize(Roles = "Admin, Moder, User")]
        public ActionResult Create(int id)
        {
            // Получаем запись темы из БД
            Topic topic = db.Topics.FirstOrDefault(p => p.Id == id);
            // Если такая запись есть
            if (topic != null)
            {
                // формируем набор данных для формы
                ViewBag.TopicName = topic.Name;
                MessageEdit model = new MessageEdit();
                model.Id = -1;
                model.Disabled = false;
                model.TopicId = id;
                // выводим форму
                return View(model);
            }
            // иначе выводим страницу с ошибкой
            else
            {
                return View("Error");
            }
        }
        // Обработка формы добавления сообщения
        [Authorize(Roles = "Admin, Moder, User")]
        [HttpPost]
        public ActionResult Create(MessageEdit model)
        {
            if (ModelState.IsValid)
            {
                // Получаем записи темы и пользователя из БД
                Topic topic = db.Topics.FirstOrDefault(p => p.Id == model.TopicId);
                User user = db.Users.FirstOrDefault(p => p.Login == User.Identity.Name);
                // Если такие записи есть
                if (topic != null && user != null)
                {
                    // сохраняем сообщение в БД
                    Message msg = new Message();
                    msg.Content = model.Content;
                    msg.Dates = DateTime.Now;
                    msg.Disabled = false;
                    msg.Topic = topic;
                    msg.User = user;
                    db.Messages.Add(msg);
                    db.SaveChanges();
                    // редирект к списку сообщений
                    return RedirectToAction("List/" + model.TopicId);
                }
                else
                {
                    ModelState.AddModelError("", "Ошибка при сохранении");
                }
            }
            return View(model);
        }
        // Форма редактирования сообщения
        [Authorize(Roles = "Admin, Moder, User")]
        public ActionResult Edit(int? id)
        {
            // Получаем записи сообщения и текущего пользователя из БД
            Message msg = db.Messages.Include("Topic").FirstOrDefault(p => p.Id == id);
            User user = db.Users.FirstOrDefault(p => p.Login == User.Identity.Name);
            // Если такие записи есть
            if (msg != null && user != null)
            {
                // Пользователь может редактировать только свои записи
                // Если это не запись пользователя, то выводим страницу с ошибкой
                if (user.RoleId == 3 && user.Id != msg.UserId)
                {
                    return View("Error");
                }
                // формируем набор данных для формы
                ViewBag.TopicName = msg.Topic.Name;
                MessageEdit model = new MessageEdit();
                model.Id = msg.Id;
                model.Disabled = msg.Disabled;
                model.TopicId = (int)msg.TopicId;
                model.Content = msg.Content;
                // выводим форму
                return View(model);
            }
            else
            {
                return View("Error");
            }
        }
        // ОБработка редактирования сообщения
        [Authorize(Roles = "Admin, Moder, User")]
        [HttpPost]
        public ActionResult Edit(MessageEdit model)
        {
            if (ModelState.IsValid)
            {
                Message msg = db.Messages.FirstOrDefault(p => p.Id == model.Id);
                if (msg != null)
                {
                    msg.Content = model.Content;
                    msg.Disabled = model.Disabled;
                    db.Entry(msg).State = EntityState.Modified;
                    db.SaveChanges();
                    return RedirectToAction("List/" + model.TopicId);
                }
                else
                {
                    ModelState.AddModelError("", "Ошибка при сохранении");
                }
            }
            return View(model);
        }
        // Вывод формы удаления сообщения
        [Authorize(Roles = "Admin, Moder, User")]
        public ActionResult Delete(int? id)
        {
            Message msg = db.Messages.Include("Topic").FirstOrDefault(p => p.Id == id);
            User user = db.Users.FirstOrDefault(p => p.Login == User.Identity.Name);
            if (msg != null && user != null)
            {
                if (user.RoleId == 3 && user.Id != msg.UserId)
                {
                    return View("Error");
                }
                ViewBag.TopicName = msg.Topic.Name;
                MessageEdit model = new MessageEdit();
                model.Id = msg.Id;
                model.Disabled = msg.Disabled;
                model.TopicId = (int)msg.TopicId;
                model.Content = msg.Content;
                return View(model);
            }
            else
            {
                return View("Error");
            }
        }
        // Обработка удаления сообщения
        [Authorize(Roles = "Admin, Moder, User")]
        [HttpPost]
        public ActionResult Delete(int? id, int? page)
        {
            Message msg = db.Messages.Include("Topic").FirstOrDefault(p => p.Id == id);
            if (msg != null)
            {
                int topicId = (int)msg.TopicId;
                db.Messages.Remove(msg);
                db.SaveChanges();
                return RedirectToAction("List/" + topicId);
            }
            else
            {
                return View("Error");
            }
        }

    }
}
