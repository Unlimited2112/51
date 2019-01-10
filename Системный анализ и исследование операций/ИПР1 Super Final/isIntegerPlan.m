function isIntegerPlan = isIntegerPlan(x)
    l = length(x);
    isIntegerPlan = 1;
    for i = 1:l
        if ~isInteger(x(i))
            isIntegerPlan = 0;
            break;
        end;
    end;
end