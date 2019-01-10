namespace WindowsFormsApplication.Model
{
    using System;
    using System.Collections.Generic;
    using System.ComponentModel.DataAnnotations;
    using System.ComponentModel.DataAnnotations.Schema;
    using System.Data.Entity.Spatial;

    public partial class Cashflow
    {
        public int Id { get; set; }

        [Required]
        [StringLength(25)]
        public string Description { get; set; }

        public double Summa { get; set; }

        public long DirectionId { get; set; }

        public virtual Direction Direction { get; set; }
    }
}
