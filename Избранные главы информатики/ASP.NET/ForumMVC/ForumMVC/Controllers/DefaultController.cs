using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using ForumMVC.Models;
using System.Data.Entity;

namespace ForumMVC.Controllers
{
    // Контроллер главной страницы со списком разделов
    public class DefaultController : Controller
    {
        ForumContext db = new ForumContext();
        // Вывод списка разделов
        public ActionResult Index()
        {
            IEnumerable<Rubric> rubrics = db.Rubrics;
            ViewBag.Rubrics = rubrics;
            return View();
        }
        // Вывод формы создания раздела
        // доступна только администратору
        [Authorize(Roles = "Admin")]
        public ActionResult Create()
        {
            return View(new Rubric());
        }
        // Обработка формы создания раздела
        [Authorize(Roles="Admin")]
        [HttpPost]
        public ActionResult Create(Rubric model)
        {
            if (ModelState.IsValid)
            {
                db.Rubrics.Add(model);
                db.SaveChanges();
                return RedirectToAction("Index");
            }
            else
            {
                ModelState.AddModelError("", "Ошибка при сохранении");
            }
            return View(model);
        }
        // Вывод формы редактирования раздела
        [Authorize(Roles = "Admin")]
        public ActionResult Edit(int? id)
        {
            Rubric rubric = db.Rubrics.FirstOrDefault(p => p.Id == id);
            if (rubric != null)
            {
                return View(rubric);
            }
            else
            {
                return View("Error");
            }
        }
        // Обработка формы редактирования раздела
        [Authorize(Roles = "Admin")]
        [HttpPost]
        public ActionResult Edit(Rubric model)
        {
            // Если модель валидна
            if (ModelState.IsValid)
            {
                // Получаем запись раздела из БД
                Rubric rubric = db.Rubrics.FirstOrDefault(p => p.Id == model.Id);
                // Если такая запись есть
                if (rubric != null)
                {
                    // изменяем ее и сохраняем в БД
                    rubric.Name = model.Name;
                    db.Entry(rubric).State = EntityState.Modified;
                    db.SaveChanges();
                    // редирект к списку разделов
                    return RedirectToAction("Index");
                }
                // иначе добавляем сообщение об ошибке
                else
                {
                    ModelState.AddModelError("", "Ошибка при сохранении");
                }
            }
            return View(model);
        }

    }
}
