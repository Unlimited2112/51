function solveLPMax = solveLPMax(f, A, b, lb, ub)
    l = length(f);
    for i = 1:l
        f(i) = -f(i);
    end;
    options = optimoptions('linprog','Display','off');
    [x0,fval,exitflag] = linprog(f, [], [], A, b, lb, ub, [], options);
    if exitflag == 1
        solveLPMax = x0;
    else
        solveLPMax = 0;
    end;
end

