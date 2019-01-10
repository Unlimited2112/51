namespace WindowsFormsApplication.Model
{
    using System;
    using System.Data.Entity;
    using System.ComponentModel.DataAnnotations.Schema;
    using System.Linq;

    public partial class CashflowsContext : DbContext
    {
        public CashflowsContext()
            : base("name=CashflowsContext")
        {
        }

        public virtual DbSet<Cashflow> Cashflows { get; set; }
        public virtual DbSet<Direction> Directions { get; set; }

        protected override void OnModelCreating(DbModelBuilder modelBuilder)
        {
            modelBuilder.Entity<Direction>()
                .HasMany(e => e.Cashflows)
                .WithRequired(e => e.Direction)
                .WillCascadeOnDelete(false);
        }
    }
}
